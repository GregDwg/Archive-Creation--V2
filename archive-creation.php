<?php
// On charge l'en-tête du site
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <header class="page-header">
            <h1 class="page-title">Galerie des Créations</h1>
        </header>

        <?php
        // On vérifie s'il y a des créations à afficher
        if ( have_posts() ) : ?>


            <div class="iaflux-filters-container">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/creation/' ) ); ?>">
                    <label>
                        <span class="screen-reader-text">Rechercher :</span>
                        <input type="search" class="search-field" placeholder="Rechercher dans les créations…" value="<?php echo get_search_query(); ?>" name="s" />
                    </label>
                    
                        <select name="modele_filter" id="modele_filter">
                            <option value="">Tous les modèles</option>
                            <?php
                            // Requête pour trouver toutes les valeurs uniques du champ 'modele_utilise'
                            global $wpdb;
                            $meta_key = 'modele_utilise';
                            $used_models = $wpdb->get_col($wpdb->prepare(
                                "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value != ''",
                                $meta_key
                            ));
                        
                            // On récupère les "libellés" depuis la définition du champ ACF pour avoir les jolis noms
                            $field = get_field_object('field_66f1c4e9c7d41'); // N'oublie pas de remettre TON field ID ici !
                            $choices = $field['choices'];
                        
                            if ( !empty($used_models) ) {
                                foreach ( $used_models as $value ) {
                                    $label = isset($choices[$value]) ? $choices[$value] : $value;
                                    echo '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    }
                    ?>
            
                    <input type="submit" class="search-submit" value="Filtrer" />
                </form>
            </div>            
            

            <div class="iaflux-gallery-grid">
            
            <?php
            // On démarre la boucle pour afficher chaque création
            while ( have_posts() ) :
                the_post();

                $image = get_field('image_de_la_creation');
                $post_url = get_permalink();
                
                // On ne crée une "brique" que si l'image existe
                if( !empty($image) ):
                ?>
                    <div class="iaflux-gallery-item">
                        <a href="<?php echo esc_url($post_url); ?>">
                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                        </a>
                    </div>
                <?php
                endif;

            endwhile; // Fin de la boucle
            ?>

            </div><?php
        else :
            // Message si aucune création n'a été trouvée
            echo '<p>Aucune création trouvée.</p>';
        endif;
        ?>

    </main></div><?php
// On charge le pied de page
get_footer();
