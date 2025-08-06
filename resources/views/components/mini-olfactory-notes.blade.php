@php
use App\Services\OlfactoryNotesService;
$structuredNotes = OlfactoryNotesService::getProductNotes($product);
$hasNotes = array_filter($structuredNotes);
@endphp

@if($hasNotes)
<div class="mini-olfactory-preview">
    @foreach(['head', 'heart', 'base'] as $category)
        @if(count($structuredNotes[$category]) > 0)
            <div class="mini-category" data-category="{{ $category }}">
                @foreach(array_slice($structuredNotes[$category], 0, 3) as $noteData)
                    <div class="mini-note" 
                         style="background-color: {{ $noteData['color'] }}20; border-left: 3px solid {{ $noteData['color'] }}"
                         title="{{ $noteData['name'] }} ({{ ucfirst($noteData['type']) }})">
                        <img src="{{ $noteData['image'] }}" alt="{{ $noteData['name'] }}" class="mini-note-image">
                    </div>
                @endforeach
                @if(count($structuredNotes[$category]) > 3)
                    <span class="more-notes">+{{ count($structuredNotes[$category]) - 3 }}</span>
                @endif
            </div>
        @endif
    @endforeach
</div>
@endif

<style>
.mini-olfactory-preview {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
    flex-wrap: wrap;
}

.mini-category {
    display: flex;
    gap: 0.3rem;
    align-items: center;
}

.mini-note {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    padding: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease;
}

.mini-note:hover {
    transform: scale(1.2);
}

.mini-note-image {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    object-fit: cover;
}

.more-notes {
    font-size: 0.7rem;
    color: var(--guerlain-text-gray);
    font-weight: 600;
    margin-left: 0.2rem;
}
</style>
