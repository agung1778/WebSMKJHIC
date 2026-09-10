@props([
    'showView' => false,
    'viewUrl' => null,
    'editUrl' => null,
    'deleteUrl' => null,
    'deleteTitle' => 'Konfirmasi',
    'deleteMessage' => 'Apakah Anda yakin ingin menghapus data ini?',
])
<div class="act-cell">
    @if($showView && $viewUrl)
        <a href="{{ $viewUrl }}" class="act-btn view" title="Lihat">
            <i class="fa-solid fa-eye"></i>
        </a>
    @endif
    @if($editUrl)
        <a href="{{ $editUrl }}" class="act-btn edit" title="Edit">
            <i class="fa-solid fa-pen"></i>
        </a>
    @endif
    @if($deleteUrl)
        <form action="{{ $deleteUrl }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="act-btn delete" title="Hapus"
                data-confirm-form data-confirm-title="{{ $deleteTitle }}" data-confirm-message="{{ $deleteMessage }}" data-confirm-danger>
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </form>
    @endif
</div>