<?php get_header(); 

/*
Template Name: FAQ
*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Réseau Alumni</title>
   
</head>
<body>
    <div class="faq-accordion">

    <h1 class="faq-title">Questions fréquentes</h1>
    <p class="faq-description">Questions fréquentes est là pour vous aider à naviguer. Vous y trouverez des réponses aux questions les plus fréquentes sur l’inscription, les événements, ou encore les services de mentorat. Cet espace est conçu pour faciliter votre expérience.
    Si une question n’est pas couverte ici, n’hésitez pas à nous contacter directement : nous sommes là pour vous accompagner et vous fournir toutes les informations nécessaires.</p>
       
    <div class="faq-item">
            <button class="faq-question blue">
                Quels sont les avantages de faire partie du réseau de Remmind ?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                <p>Remmind permet de rester connecté avec d'anciens camarades de promotions, de profiter d’opportunités professionnelles,
                d’accéder aux informations et différents portraits de MMI, et de se rapeller de leurs parcours professionnels.</p>
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question white">
                Comment s'inscrire sur Remmind
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                <p>Pour créer un compte, cliquez sur "Espace Membre", remplissez vos informations personnelles, et nos modérateurs s'occuperont de valider votre inscription. C’est simple et rapide !</p>
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question blue">
                Qui peut rejoindre la plateforme Remmind ?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                <p>Tous les étudiants et anciens diplômés du programme peuvent s’inscrire pour accéder à une multitude de services.</p>
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question white">
                Est-il possible de contacter un Alumni pour discuter de son parcours professionnel ?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                <p>Oui, il est possible de contacter des Alumni via notre plateforme pour discuter de leur parcours professionnel. Vous pouvez aussi consulter leurs portraits..</p>
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question blue">
                Comment bénéficier de l'aide des Alumnis pour la recherche d'emploi ou de stage ?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                <p>C’est simple, inscrivez-vous sur la plateforme Alumni, vous pourrez les contacter pour des conseils, des opportunités professionnelles ou consulter les différentes offres proposées.</p>
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question white">
                Comment mettre à jour ses informations de profil ?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                <p>Connectez-vous à votre compte, accéder à la page "Mon Profil'. Vous pourrez y modifier vos informations personnelles et enregistrer les changements.</p>
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question blue">
                Qui contacter pour de l’aide concernant mon compte
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                <p>Pour obtenir de l'aide concernant votre compte ou l'accès aux ressources Alumni de l'IUT Nord Franche-Comté, vous pouvez contacter le support via l'adresse email dédiée</p>
            </div>
        </div>
    </div>

    <?php get_footer(); ?>

    <script>
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const faqItem = button.parentElement;
                const answer = faqItem.querySelector('.faq-answer');

                // Fermer les autres FAQ ouvertes
                document.querySelectorAll('.faq-item.active').forEach(activeItem => {
                    if (activeItem !== faqItem) {
                        activeItem.classList.remove('active');
                        activeItem.querySelector('.faq-answer').style.display = 'none'; // Masquer la réponse
                    }
                });

                // Alterner l'état actif
                faqItem.classList.toggle('active');

                // Afficher/Masquer la réponse
                if (faqItem.classList.contains('active')) {
                    answer.style.display = 'block';
                } else {
                    answer.style.display = 'none';
                }
            });
        });
    </script>

<style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .faq-accordion {
            max-width: 1100px;
            margin: auto;
            margin-top: 1rem;
            margin-bottom: 2rem;
            padding: 20px;
        }
        .faq-title {
            font-family : 'Aristotelica Display Trial', sans-serif;
            margin-bottom: 20px;
            font-size: 2rem;
            text-transform: uppercase;
            color: #002C40;
        }

        .faq-description {
            margin-bottom: 3rem;
        }

        .faq-item {
            margin-bottom: 10px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .faq-question {
            position: relative;
            width: 100%;
            text-align: left;
            padding: 15px 40px 15px 15px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .faq-question.blue {
            background-color: #002C40;;
            color: #ffffff;
        }

        .faq-question.white {
            background-color: #ffffff;
            color: #333333;
        }

        .faq-question .arrow {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .faq-item.active .arrow {
            transform: translateY(-50%) rotate(180deg);
        }

        .faq-answer {
            display: none;
            padding: 15px;
            background-color: #f9f9f9;
            border-top: 1px solid #e0e0e0;
        }

        .faq-answer p {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
        }

        @media screen and (max-width: 600px) {
            .faq-question {
                font-size: 14px;
                padding: 12px 40px 12px 12px;
            }

            .faq-answer p {
                font-size: 13px;
            }
        }
    </style>
</body>
</html>
