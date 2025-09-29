(function($) {
    $(document).ready(function() {
        
        // La fonction qui envoie la requête AJAX
        function get_filtered_posts() {
            var search = $('#search_filter').val();
            var modele = $('#modele_filter').val();
            
            // On affiche un effet de chargement
            $('.iaflux-gallery-grid').html('<p class="iaflux-loading">Chargement...</p>');

            $.ajax({
                url: iaflux_ajax_obj.ajax_url,
                type: 'POST',
                data: {
                    action: 'iaflux_filter',
                    nonce: iaflux_ajax_obj.nonce,
                    search: search,
                    modele: modele
                },
                success: function(response) {
                    // On remplace le contenu de la grille et on relance Masonry
                    var grid = $('.iaflux-gallery-grid');
                    grid.html(response);
                    
                    var msnry = new Masonry( grid[0], {
                        itemSelector: '.iaflux-gallery-item',
                        columnWidth: '.iaflux-gallery-item',
                        gutter: 15
                    });
                },
                error: function() {
                    $('.iaflux-gallery-grid').html('<p>Une erreur est survenue.</p>');
                }
            });
        }

        // On écoute les événements (frappe au clavier et changement du menu)
        var timer;
        $('#search_filter').on('keyup', function() {
            clearTimeout(timer);
            timer = setTimeout(get_filtered_posts, 500); // On attend 500ms après la dernière frappe
        });

        $('#modele_filter').on('change', function() {
            get_filtered_posts();
        });
        
        // On modifie aussi le formulaire pour qu'il ne se soumette plus de manière classique
        $('#iaflux_filter_form').on('submit', function(e) {
            e.preventDefault(); // On empêche le rechargement de la page
            get_filtered_posts();
        });
    });
})(jQuery);
