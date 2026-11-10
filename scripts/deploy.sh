#!/usr/bin/env bash
# Shared-hosting deployment. Invoke with: bash scripts/deploy.sh
set -Eeuo pipefail

fail() { printf 'GAGAL: %s\n' "$*" >&2; exit 1; }
info() { printf '%s\n' "$*"; }

usage() {
    cat <<'HELP'
Pemakaian: bash scripts/deploy.sh [opsi]

  --public-path PATH  Document root domain (contoh: /home/akun/public_html)
  --dry-run           Periksa dan tampilkan rencana tanpa mengubah apa pun
  --skip-pull         Lewati git pull jika sudah dijalankan sebelumnya
  --skip-cache        Hanya sinkronkan aset, tanpa perintah Artisan
  --help              Tampilkan bantuan

Konfigurasi sekali melalui .deploy.env di folder project.
Tidak menyalin .env, index.php, .htaccess, atau file unggahan.
Tidak menghapus aset, menjalankan migrasi, atau membuat ulang APP_KEY.
HELP
}

validate_target() {
    local relative="$1" current="$PUBLIC_PATH" part
    local -a parts
    IFS='/' read -r -a parts <<< "$relative"
    for part in "${parts[@]}"; do
        current="$current/$part"
        [[ ! -L "$current" ]] || fail "Target berupa symlink; tidak diubah: $current"
    done
    [[ ! -d "$current" ]] || fail "Target file ternyata folder: $current"
}

cleanup() {
    if [[ -n "${TEMP_FILE:-}" && -f "$TEMP_FILE" ]]; then rm -f -- "$TEMP_FILE"; fi
    if [[ "${LOCK_HELD:-0}" == 1 ]]; then rmdir -- "$LOCK_PATH"; fi
}

main() {
    local ROOT CONFIG DRY_RUN=0 SKIP_PULL=0 SKIP_CACHE=0 relative source resolved target parent
    ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd -P)"
    CONFIG="$ROOT/.deploy.env"
    if [[ -f "$CONFIG" ]]; then source "$CONFIG"; fi
    PUBLIC_PATH="${DEPLOY_PUBLIC_PATH:-}"
    local PHP_BIN="${DEPLOY_PHP_BIN:-php}" BRANCH="${DEPLOY_BRANCH:-master}"

    while (($#)); do
        case "$1" in
            --public-path)
                (($# >= 2)) || fail '--public-path membutuhkan lokasi folder.'
                PUBLIC_PATH="$2"; shift 2 ;;
            --dry-run) DRY_RUN=1; shift ;;
            --skip-pull) SKIP_PULL=1; shift ;;
            --skip-cache) SKIP_CACHE=1; shift ;;
            --help|-h) usage; return ;;
            *) fail "Opsi tidak dikenal: $1" ;;
        esac
    done

    command -v git >/dev/null || fail 'Git tidak tersedia.'
    command -v realpath >/dev/null || fail 'Perintah realpath tidak tersedia.'
    git -C "$ROOT" rev-parse --is-inside-work-tree >/dev/null 2>&1 || fail 'Folder project bukan repository Git.'
    [[ -n "$PUBLIC_PATH" ]] || fail 'Isi DEPLOY_PUBLIC_PATH di .deploy.env, atau gunakan --public-path.'
    [[ "$PUBLIC_PATH" == /* ]] || fail 'Document root harus menggunakan path absolut.'
    [[ -d "$PUBLIC_PATH" ]] || fail "Document root tidak ditemukan: $PUBLIC_PATH"
    PUBLIC_PATH="$(cd -- "$PUBLIC_PATH" && pwd -P)"
    [[ "$PUBLIC_PATH" != / && "$ROOT" != "$PUBLIC_PATH" && "$ROOT" != "$PUBLIC_PATH/"* ]] || fail 'Document root tidak boleh root sistem, folder project, atau induk folder project.'
    [[ -w "$PUBLIC_PATH" ]] || fail "Document root tidak dapat ditulis: $PUBLIC_PATH"
    if [[ "$SKIP_CACHE" == 0 ]]; then
        command -v "$PHP_BIN" >/dev/null || fail 'PHP CLI tidak ditemukan. Atur DEPLOY_PHP_BIN.'
        [[ -f "$ROOT/vendor/autoload.php" ]] || fail 'Dependency PHP belum tersedia. Jalankan composer install terlebih dahulu.'
        [[ -f "$ROOT/.env" ]] || fail '.env server belum tersedia; script tidak membuat atau menggantinya.'
    fi

    info "Project: $ROOT"
    info "Document root: $PUBLIC_PATH"
    if [[ "$SKIP_PULL" == 0 ]]; then
        [[ "$(git -C "$ROOT" branch --show-current)" == "$BRANCH" ]] || fail "Branch aktif harus $BRANCH. Script tidak mengganti branch secara otomatis."
        git -C "$ROOT" diff --quiet && git -C "$ROOT" diff --cached --quiet || fail 'Ada perubahan lokal. Simpan/tinjau dahulu; script tidak akan membuangnya.'
    fi

    TEMP_FILE=''
    LOCK_HELD=0
    LOCK_PATH="$ROOT/.deploy.lock"
    trap cleanup EXIT
    if [[ "$DRY_RUN" == 0 ]]; then
        mkdir -- "$LOCK_PATH" 2>/dev/null || fail 'Deploy lain sedang berjalan, atau .deploy.lock masih ada. Periksa proses sebelum menghapus lock tersebut.'
        LOCK_HELD=1
    fi
    if [[ "$SKIP_PULL" == 0 ]]; then
        if [[ "$DRY_RUN" == 1 ]]; then
            info "[dry-run] git pull --ff-only origin $BRANCH (tidak dijalankan)"
        else
            git -C "$ROOT" pull --ff-only origin "$BRANCH"
        fi
    fi

    local -a ASSETS=()
    while IFS= read -r -d '' source; do
        relative="${source#public/}"
        case "$relative" in
            admin/*|assets/*|build/*|css/*|js/*|favicon.ico|robots.txt|manifest.json|manifest.webmanifest) ;;
            *) continue ;;
        esac
        case "${relative##*.}" in
            css|js|mjs|map|json|webmanifest|jpg|jpeg|png|gif|webp|avif|svg|ico|woff|woff2|ttf|otf|eot|pdf|txt|mp4|webm) ;;
            *) continue ;;
        esac
        [[ -f "$ROOT/$source" && ! -L "$ROOT/$source" ]] || fail "Aset sumber hilang atau berupa symlink: $source"
        resolved="$(realpath -- "$ROOT/$source")"
        [[ "$resolved" == "$ROOT/public/"* ]] || fail "Aset sumber berada di luar public: $source"
        validate_target "$relative"
        ASSETS+=("$relative")
    done < <(git -C "$ROOT" ls-files -z -- public)
    ((${#ASSETS[@]} > 0)) || fail 'Tidak ada aset publik terdaftar di Git.'

    local copied=0 unchanged=0
    for relative in "${ASSETS[@]}"; do
        source="$ROOT/public/$relative"
        target="$PUBLIC_PATH/$relative"
        if [[ "$ROOT/public" == "$PUBLIC_PATH" ]] || { [[ -f "$target" ]] && cmp -s -- "$source" "$target"; }; then
            unchanged=$((unchanged + 1)); continue
        fi
        if [[ "$DRY_RUN" == 1 ]]; then
            info "[dry-run] Salin $relative"
        else
            parent="$(dirname -- "$target")"
            mkdir -p -- "$parent"
            TEMP_FILE="$(mktemp "$parent/.deploy-XXXXXXXX")"
            cp -- "$source" "$TEMP_FILE"
            chmod 644 "$TEMP_FILE"
            touch -r "$source" "$TEMP_FILE"
            mv -f -- "$TEMP_FILE" "$target"
            TEMP_FILE=''
        fi
        copied=$((copied + 1))
    done

    if [[ "$SKIP_CACHE" == 0 ]]; then
        if [[ "$DRY_RUN" == 1 ]]; then
            info '[dry-run] Artisan route:clear, view:clear, config:cache, view:cache (tidak dijalankan)'
        else
            (cd -- "$ROOT" && "$PHP_BIN" artisan route:clear && "$PHP_BIN" artisan view:clear && "$PHP_BIN" artisan config:cache && "$PHP_BIN" artisan view:cache)
        fi
    fi
    if [[ "$DRY_RUN" == 1 ]]; then
        info "Pemeriksaan selesai: $copied aset perlu disalin; $unchanged aset sudah sama. Tidak ada perubahan."
    else
        info "Deploy selesai: $copied aset disalin; $unchanged aset sudah sama. Database dan file unggahan tidak diubah."
        info 'Bersihkan cache CDN/hosting jika digunakan, lalu hard refresh browser.'
    fi
}

main "$@"
