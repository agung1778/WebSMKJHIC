#!/usr/bin/env bash
# Standalone integration tests. Uses only new local Git repositories and fake PHP.
set -Eeuo pipefail
ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd -P)"
FIXTURE="$(mktemp -d "${DEPLOY_TEST_TEMP_ROOT:-${TMPDIR:-/tmp}}/websmkjhic-deploy-test-XXXXXXXX")"
FIXTURE="$(cd -- "$FIXTURE" && pwd -P)"
REPO="$FIXTURE/project"
DOCROOT="$FIXTURE/domain/public_html"
mkdir -p "$REPO/scripts" "$REPO/public/admin" "$REPO/public/assets/image" "$REPO/public/storage" "$REPO/vendor" "$DOCROOT/admin" "$FIXTURE/bin"
cp "$ROOT/scripts/deploy.sh" "$REPO/scripts/deploy.sh"
printf 'new-theme\n' > "$REPO/public/admin/admin-theme.css"
printf 'new-script\n' > "$REPO/public/admin/admin.js"
printf 'photo\n' > "$REPO/public/assets/image/DroneView.jpg"
printf 'do-not-publish\n' > "$REPO/public/admin/private.php"
printf 'do-not-publish\n' > "$REPO/public/index.php"
printf 'do-not-publish\n' > "$REPO/public/.htaccess"
printf 'do-not-copy-uploads\n' > "$REPO/public/storage/upload.txt"
printf 'LOCAL_ONLY_SENTINEL\n' > "$REPO/.env"
printf 'original-domain-index\n' > "$DOCROOT/index.php"
printf 'original-domain-env\n' > "$DOCROOT/.env"
printf 'preserve-existing\n' > "$DOCROOT/admin/extra.css"
printf 'original-domain-rules\n' > "$DOCROOT/.htaccess"
printf 'old-theme\n' > "$DOCROOT/admin/admin-theme.css"
touch "$REPO/vendor/autoload.php" "$REPO/artisan"
printf '.env\n.deploy.env\n.deploy.lock/\nvendor/\n' > "$REPO/.gitignore"
printf '* text=auto eol=lf\n' > "$REPO/.gitattributes"
cat > "$FIXTURE/bin/php-test" <<'PHP'
#!/usr/bin/env bash
printf '%s\n' "$*" >> "$DEPLOY_PHP_LOG"
if [[ "${DEPLOY_FAKE_PHP_FAIL:-0}" == 1 ]]; then exit 7; fi
PHP
chmod +x "$FIXTURE/bin/php-test"
export DEPLOY_PHP_BIN="$FIXTURE/bin/php-test"
export DEPLOY_PHP_LOG="$FIXTURE/php.log"
git -C "$REPO" init -q -b master
git -C "$REPO" config user.email deploy-test@example.invalid
git -C "$REPO" config user.name 'Deployment test'
git -C "$REPO" add .
git -C "$REPO" commit -qm 'Fixture'
git init -q --bare "$FIXTURE/remote.git"
git -C "$REPO" remote add origin "$FIXTURE/remote.git"
git -C "$REPO" push -q -u origin master

run() { bash "$REPO/scripts/deploy.sh" --public-path "$DOCROOT" "$@"; }
reject() {
    if "$@" > "$FIXTURE/rejection.log" 2>&1; then
        printf 'FAIL: command should have been rejected\n' >&2; exit 1
    fi
}

run --dry-run > "$FIXTURE/dry-run.log"
grep -q 'old-theme' "$DOCROOT/admin/admin-theme.css"
[[ ! -f "$DEPLOY_PHP_LOG" && ! -d "$REPO/.deploy.lock" ]]
printf 'PASS: dry-run does not copy files, pull, run Artisan, or create a lock\n'

run > "$FIXTURE/deploy.log"
cmp "$REPO/public/admin/admin-theme.css" "$DOCROOT/admin/admin-theme.css"
cmp "$REPO/public/admin/admin.js" "$DOCROOT/admin/admin.js"
cmp "$REPO/public/assets/image/DroneView.jpg" "$DOCROOT/assets/image/DroneView.jpg"
grep -q 'original-domain-index' "$DOCROOT/index.php"
grep -q 'original-domain-env' "$DOCROOT/.env"
grep -q 'original-domain-rules' "$DOCROOT/.htaccess"
grep -q 'preserve-existing' "$DOCROOT/admin/extra.css"
[[ ! -e "$DOCROOT/admin/private.php" && ! -e "$DOCROOT/storage/upload.txt" ]]
printf 'artisan view:clear\nartisan config:cache\nartisan view:cache\n' > "$FIXTURE/expected-php.log"
cmp "$DEPLOY_PHP_LOG" "$FIXTURE/expected-php.log"
[[ ! -d "$REPO/.deploy.lock" ]]
printf 'PASS: assets updated; domain entrypoint, rules, env, extra assets and uploads preserved; only intended cache commands run\n'

run --skip-pull --skip-cache > "$FIXTURE/repeat.log"
grep -q '0 aset disalin' "$FIXTURE/repeat.log"
printf 'PASS: repeating deployment skips unchanged assets\n'

bash "$REPO/scripts/deploy.sh" --public-path "$REPO/public" --skip-pull --skip-cache > "$FIXTURE/same-root.log"
grep -q '0 aset disalin' "$FIXTURE/same-root.log"
printf 'PASS: document roots pointing directly at project/public require no copying\n'

printf 'DEPLOY_PUBLIC_PATH=%q\n' "$DOCROOT" > "$REPO/.deploy.env"
bash "$REPO/scripts/deploy.sh" --skip-pull --skip-cache > "$FIXTURE/config.log"
grep -q '0 aset disalin' "$FIXTURE/config.log"
printf 'PASS: one-time local configuration supplies the document root\n'

reject bash "$REPO/scripts/deploy.sh" --public-path "$REPO" --skip-pull --skip-cache
reject bash "$REPO/scripts/deploy.sh" --public-path "$FIXTURE" --skip-pull --skip-cache
printf 'PASS: project directory and its parent cannot be used as document roots\n'

mkdir "$REPO/.deploy.lock"
reject run --skip-pull --skip-cache
rmdir "$REPO/.deploy.lock"
printf 'PASS: simultaneous deployment is rejected\n'

printf 'local-edit\n' >> "$REPO/public/admin/admin.js"
reject run --dry-run
git -C "$REPO" add public/admin/admin.js
git -C "$REPO" commit -qm 'Retain local edit in fixture history'
git -C "$REPO" push -q origin master
printf 'PASS: local changes are not discarded\n'

git clone -q "$FIXTURE/remote.git" "$FIXTURE/editor"
git -C "$FIXTURE/editor" config user.email deploy-test@example.invalid
git -C "$FIXTURE/editor" config user.name 'Deployment test'
printf 'pulled-theme\n' > "$FIXTURE/editor/public/admin/admin-theme.css"
git -C "$FIXTURE/editor" add .
git -C "$FIXTURE/editor" commit -qm 'Remote theme update'
git -C "$FIXTURE/editor" push -q origin master
run --skip-cache > "$FIXTURE/pull.log"
grep -q 'pulled-theme' "$DOCROOT/admin/admin-theme.css"
printf 'PASS: remote updates are fast-forwarded and published in one invocation\n'

export DEPLOY_FAKE_PHP_FAIL=1
reject run --skip-pull
[[ ! -d "$REPO/.deploy.lock" ]]
unset DEPLOY_FAKE_PHP_FAIL
printf 'PASS: Artisan failures propagate and release the deployment lock\n'

mkdir "$FIXTURE/linked-domain" "$FIXTURE/outside"
if ln -s "$FIXTURE/outside" "$FIXTURE/linked-domain/assets" 2>/dev/null && [[ -L "$FIXTURE/linked-domain/assets" ]]; then
    reject bash "$REPO/scripts/deploy.sh" --public-path "$FIXTURE/linked-domain" --skip-pull --skip-cache
    [[ ! -e "$FIXTURE/outside/image/DroneView.jpg" && ! -d "$REPO/.deploy.lock" ]]
    printf 'PASS: symlink targets cannot redirect writes outside the domain\n'
else
    printf 'SKIP: symlink creation is not supported in this environment\n'
fi
printf 'Integration tests complete. Isolated fixtures: %s\n' "$FIXTURE"
