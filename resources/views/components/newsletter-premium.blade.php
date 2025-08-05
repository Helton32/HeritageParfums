<!-- Newsletter Section - À intégrer dans welcome.blade.php -->
<section style="padding: 6rem 0; background: var(--guerlain-black); color: var(--guerlain-white); position: relative; overflow: hidden;">
    <!-- Fond avec effet de grain -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"dots\" width=\"20\" height=\"20\" patternUnits=\"userSpaceOnUse\"><circle cx=\"10\" cy=\"10\" r=\"0.8\" fill=\"rgba(212,175,55,0.1)\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23dots)\"/></svg>') repeat; opacity: 0.3; z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; font-weight: 300; margin-bottom: 2rem; color: var(--guerlain-gold); letter-spacing: 2px;">
                    Cercle Privilégié
                </h2>
                <p style="font-family: 'Montserrat', sans-serif; font-size: 1.2rem; font-weight: 300; margin-bottom: 3rem; opacity: 0.9; line-height: 1.8;">
                    Rejoignez notre cercle exclusif et découvrez en avant-première nos créations d'exception, 
                    nos conseils de parfumeurs et nos événements privés.
                </p>
                
                <!-- Formulaire Newsletter Premium -->
                <form id="newsletter-form" style="max-width: 500px; margin: 0 auto;">
                    <div style="position: relative; margin-bottom: 2rem;">
                        <input 
                            type="email" 
                            id="newsletter-email" 
                            placeholder="Votre adresse email"
                            style="width: 100%; padding: 20px 0; background: transparent; border: none; border-bottom: 2px solid rgba(255,255,255,0.3); color: var(--guerlain-white); font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 1.1rem; text-align: center; transition: all 0.3s ease;"
                            required
                        >
                        <div style="position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; background: var(--guerlain-gold); transition: all 0.3s ease; transform: translateX(-50%); z-index: 1;" id="email-underline"></div>
                    </div>
                    
                    <div style="margin-bottom: 3rem;">
                        <label style="display: flex; align-items: center; justify-content: center; cursor: pointer; font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 0.9rem; opacity: 0.8;">
                            <input type="checkbox" id="newsletter-consent" required style="margin-right: 10px; transform: scale(1.2);">
                            J'accepte de recevoir les communications exclusives Heritage Parfums
                        </label>
                    </div>
                    
                    <button 
                        type="submit" 
                        id="newsletter-btn"
                        style="background: var(--guerlain-gold); color: var(--guerlain-black); border: 2px solid var(--guerlain-gold); padding: 18px 50px; font-family: 'Montserrat', sans-serif; font-weight: 400; font-size: 13px; text-transform: uppercase; letter-spacing: 2px; border-radius: 0; transition: all 0.4s ease; cursor: pointer;"
                        onmouseover="this.style.background='transparent'; this.style.color='var(--guerlain-gold)'"
                        onmouseout="this.style.background='var(--guerlain-gold)'; this.style.color='var(--guerlain-black)'"
                    >
                        Rejoindre le Cercle
                    </button>
                </form>
                
                <!-- Message de succès -->
                <div id="newsletter-success" style="display: none; margin-top: 2rem; padding: 2rem; background: rgba(212, 175, 55, 0.1); border: 1px solid var(--guerlain-gold); color: var(--guerlain-gold);">
                    <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <h4 style="font-family: 'Cormorant Garamond', serif; margin-bottom: 1rem;">Bienvenue dans notre Cercle Privilégié !</h4>
                    <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; margin: 0;">
                        Vous recevrez prochainement votre premier message exclusif avec nos dernières créations.
                    </p>
                </div>
                
                <!-- Avantages du cercle -->
                <div style="margin-top: 4rem;">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div style="padding: 2rem; text-align: center;">
                                <i class="fas fa-crown" style="font-size: 2rem; color: var(--guerlain-gold); margin-bottom: 1rem;"></i>
                                <h5 style="font-family: 'Cormorant Garamond', serif; margin-bottom: 1rem; color: var(--guerlain-gold);">
                                    Avant-Premières
                                </h5>
                                <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 0.9rem; opacity: 0.8;">
                                    Découvrez nos nouvelles créations en exclusivité
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div style="padding: 2rem; text-align: center;">
                                <i class="fas fa-gift" style="font-size: 2rem; color: var(--guerlain-gold); margin-bottom: 1rem;"></i>
                                <h5 style="font-family: 'Cormorant Garamond', serif; margin-bottom: 1rem; color: var(--guerlain-gold);">
                                    Offres Privilégiées
                                </h5>
                                <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 0.9rem; opacity: 0.8;">
                                    Accès à des promotions réservées aux membres
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div style="padding: 2rem; text-align: center;">
                                <i class="fas fa-user-graduate" style="font-size: 2rem; color: var(--guerlain-gold); margin-bottom: 1rem;"></i>
                                <h5 style="font-family: 'Cormorant Garamond', serif; margin-bottom: 1rem; color: var(--guerlain-gold);">
                                    Conseils Experts
                                </h5>
                                <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 0.9rem; opacity: 0.8;">
                                    Astuces et secrets de nos maîtres parfumeurs
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('newsletter-email');
    const underline = document.getElementById('email-underline');
    const form = document.getElementById('newsletter-form');
    const successMessage = document.getElementById('newsletter-success');
    const submitBtn = document.getElementById('newsletter-btn');
    
    // Effet de soulignement sur focus
    emailInput.addEventListener('focus', function() {
        underline.style.width = '100%';
    });
    
    emailInput.addEventListener('blur', function() {
        if (!this.value) {
            underline.style.width = '0';
        }
    });
    
    // Validation et soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = emailInput.value;
        const consent = document.getElementById('newsletter-consent').checked;
        
        if (!email || !consent) {
            alert('Veuillez remplir tous les champs requis.');
            return;
        }
        
        // Animation du bouton
        submitBtn.style.opacity = '0.6';
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Inscription...';
        
        // Simulation d'envoi (remplacer par vraie requête AJAX)
        setTimeout(() => {
            form.style.display = 'none';
            successMessage.style.display = 'block';
            
            // Animation d'apparition
            successMessage.style.opacity = '0';
            successMessage.style.transform = 'translateY(20px)';
            setTimeout(() => {
                successMessage.style.transition = 'all 0.5s ease';
                successMessage.style.opacity = '1';
                successMessage.style.transform = 'translateY(0)';
            }, 50);
            
            // Sauvegarder dans localStorage pour éviter de redemander
            localStorage.setItem('heritage_newsletter_subscribed', 'true');
            
        }, 2000);
    });
    
    // Vérifier si déjà inscrit
    if (localStorage.getItem('heritage_newsletter_subscribed')) {
        form.style.display = 'none';
        successMessage.style.display = 'block';
        document.querySelector('#newsletter-success h4').textContent = 'Vous êtes déjà membre !';
        document.querySelector('#newsletter-success p').textContent = 'Merci de faire partie de notre cercle privilégié.';
    }
});
</script>
