@php
use App\Services\OlfactoryNotesService;
$structuredNotes = OlfactoryNotesService::getProductNotes($product);
$categoryLabels = OlfactoryNotesService::getNoteCategoryLabels();
@endphp

<div class="olfactory-notes-section">
    <h3 class="section-title">
        <i class="fas fa-spa me-2"></i>
        Pyramide Olfactive
    </h3>
    
    @if(array_filter($structuredNotes))
        <div class="olfactory-pyramid">
            @foreach(['head', 'heart', 'base'] as $category)
                @if(count($structuredNotes[$category]) > 0)
                    <div class="note-category" data-category="{{ $category }}">
                        <div class="category-header">
                            <h4 class="category-title">{{ $categoryLabels[$category] }}</h4>
                            <div class="category-icon">
                                @if($category === 'head')
                                    <i class="fas fa-cloud"></i>
                                @elseif($category === 'heart')
                                    <i class="fas fa-heart"></i>
                                @else
                                    <i class="fas fa-tree"></i>
                                @endif
                            </div>
                        </div>
                        
                        <div class="notes-grid">
                            @foreach($structuredNotes[$category] as $noteData)
                                <div class="note-item" data-type="{{ $noteData['type'] }}">
                                    <div class="note-image-container">
                                        <img src="{{ $noteData['image'] }}" 
                                             alt="{{ $noteData['name'] }}" 
                                             class="note-image"
                                             loading="lazy">
                                        <div class="note-overlay">
                                            <span class="note-type">{{ ucfirst($noteData['type']) }}</span>
                                        </div>
                                    </div>
                                    <div class="note-info">
                                        <h5 class="note-name">{{ $noteData['name'] }}</h5>
                                        <div class="note-color-indicator" 
                                             style="background-color: {{ $noteData['color'] }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        
        <!-- Légende des types -->
        <div class="notes-legend">
            <h5>Légende</h5>
            <div class="legend-items">
                <div class="legend-item">
                    <span class="legend-icon" style="background: #4CAF50"></span>
                    <span>Agrumes & Herbes</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon" style="background: #E91E63"></span>
                    <span>Fleurs</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon" style="background: #FF9800"></span>
                    <span>Fruits</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon" style="background: #8D6E63"></span>
                    <span>Bois & Résines</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon" style="background: #FF8A65"></span>
                    <span>Épices</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon" style="background: #FFF3E0"></span>
                    <span>Gourmand</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon" style="background: #E3F2FD"></span>
                    <span>Actifs Cosmétiques</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon" style="background: #E8F5E8"></span>
                    <span>Huiles Naturelles</span>
                </div>
            </div>
        </div>
    @else
        <div class="no-notes">
            <div class="no-notes-icon">
                <i class="fas fa-leaf fa-3x"></i>
            </div>
            <p>Les notes olfactives de ce produit ne sont pas encore disponibles.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
.olfactory-notes-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 3rem;
    border-radius: 20px;
    margin: 3rem 0;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.section-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    font-weight: 600;
    color: var(--guerlain-black);
    text-align: center;
    margin-bottom: 2.5rem;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -1rem;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--guerlain-gold), var(--guerlain-dark-gold));
    border-radius: 2px;
}

.olfactory-pyramid {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
}

.note-category {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.note-category:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.note-category[data-category="head"] {
    border-left: 5px solid #2196F3;
}

.note-category[data-category="heart"] {
    border-left: 5px solid #E91E63;
}

.note-category[data-category="base"] {
    border-left: 5px solid #FF9800;
}

.category-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.category-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.6rem;
    font-weight: 600;
    color: var(--guerlain-black);
    margin: 0;
}

.category-icon {
    font-size: 1.5rem;
    color: var(--guerlain-gold);
    padding: 0.5rem;
    background: rgba(212, 175, 55, 0.1);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1.5rem;
}

.note-item {
    background: #fafafa;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

.note-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    border-color: var(--guerlain-gold);
}

.note-image-container {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.note-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.note-item:hover .note-image {
    transform: scale(1.1);
}

.note-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
    padding: 0.3rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.note-item:hover .note-overlay {
    opacity: 1;
}

.note-type {
    font-size: 0.6rem;
    letter-spacing: 0.5px;
}

.note-info {
    position: relative;
}

.note-name {
    font-family: 'Montserrat', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--guerlain-black);
    margin: 0 0 0.5rem 0;
    line-height: 1.3;
}

.note-color-indicator {
    width: 30px;
    height: 4px;
    margin: 0 auto;
    border-radius: 2px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.notes-legend {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin-top: 2rem;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}

.notes-legend h5 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--guerlain-black);
    margin-bottom: 1rem;
    text-align: center;
}

.legend-items {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--guerlain-text-gray);
}

.legend-icon {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: block;
}

.no-notes {
    text-align: center;
    padding: 3rem;
    color: var(--guerlain-text-gray);
}

.no-notes-icon {
    margin-bottom: 1rem;
    color: var(--guerlain-gold);
    opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .olfactory-notes-section {
        padding: 2rem 1.5rem;
        margin: 2rem 0;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .category-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .notes-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
    }
    
    .note-image-container {
        width: 60px;
        height: 60px;
    }
    
    .legend-items {
        justify-content: flex-start;
        gap: 0.8rem;
    }
    
    .legend-item {
        font-size: 0.8rem;
    }
}

/* Animation au chargement */
.note-item {
    opacity: 0;
    animation: fadeInUp 0.6s ease forwards;
}

.note-item:nth-child(1) { animation-delay: 0.1s; }
.note-item:nth-child(2) { animation-delay: 0.2s; }
.note-item:nth-child(3) { animation-delay: 0.3s; }
.note-item:nth-child(4) { animation-delay: 0.4s; }
.note-item:nth-child(5) { animation-delay: 0.5s; }
.note-item:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Effets de survol spéciaux */
.note-category[data-category="head"]:hover {
    background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
}

.note-category[data-category="heart"]:hover {
    background: linear-gradient(135deg, #FCE4EC 0%, #F8BBD9 100%);
}

.note-category[data-category="base"]:hover {
    background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
}
</style>
@endpush

@push('scripts')
<script>
// Effet de tooltip sur les notes
document.addEventListener('DOMContentLoaded', function() {
    const noteItems = document.querySelectorAll('.note-item');
    
    noteItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const noteName = this.querySelector('.note-name').textContent;
            const noteType = this.dataset.type;
            
            // Créer un tooltip personnalisé
            const tooltip = document.createElement('div');
            tooltip.className = 'note-tooltip';
            tooltip.innerHTML = `
                <strong>${noteName}</strong><br>
                <small>Type: ${noteType}</small>
            `;
            
            document.body.appendChild(tooltip);
            
            // Positionner le tooltip
            this.addEventListener('mousemove', function(e) {
                tooltip.style.cssText = `
                    position: fixed;
                    top: ${e.clientY - 60}px;
                    left: ${e.clientX + 10}px;
                    background: rgba(0,0,0,0.8);
                    color: white;
                    padding: 0.5rem;
                    border-radius: 5px;
                    font-size: 0.8rem;
                    z-index: 1000;
                    pointer-events: none;
                `;
            });
        });
        
        item.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.note-tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
});
</script>
@endpush
