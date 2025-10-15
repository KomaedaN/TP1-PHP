# 3IW2-2025

- docker build .
- docker compose up

pgAdmin:

-Copier et coller dans le "Query tool" puis run pour ajouter la table user.

CREATE TABLE IF NOT EXISTS public."user"
(
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1 ),
    email character varying(255) COLLATE pg_catalog."default" NOT NULL,
    password character varying(100) COLLATE pg_catalog."default" NOT NULL,
    firstname character varying(50) COLLATE pg_catalog."default",
    lastname character varying(50) COLLATE pg_catalog."default",
    account_activation_hash character varying(64) COLLATE pg_catalog."default" DEFAULT NULL::character varying,
    CONSTRAINT user_pkey PRIMARY KEY (id),
    CONSTRAINT user_account_activation_hash_key UNIQUE (account_activation_hash),
    CONSTRAINT user_email_key UNIQUE (email)
)


SMTP:

- Ajouter les valeurs Username et Password de votre SMTP dans le fichier www/SendEmailFunction.php
- docker compose run --rm composer composer install



ETAPE:
- Nettoyer les valeurs, exemple trim sur l'email et lowercase (5 points)

"htmlspecialchars()" sur les input affichés.
trim() pour retirer les espaces au début et à la fin.
strtolower() pour l'email.
ucfirst() pour le prénom et le nom pour mettre en majuscule la première lettre.

- Attention au mot de passe (3 points)

vérifier le mot de passe avec le mot de passe de confirmation.
password_hash() pour stocker le mot de passe en "hashé" dans la bdd.

- Attention à l'unicité de l'email (4 points)

ajout de "UNIQUE" dans ma bdd sur l'email.
verification si l'email est déjà présent dans la bdd.

- Vérifier les champs sachant que le prénom et le nom sont facultatifs

Ce sont les seuls champs sans "required" dans l'input.
Peuvent être "null" dans la bdd.

- Insérer en BDD avec PDO et des requêtes préparées si tout est OK (4 points)

PDO utilisé, mes requêtes sont préparées avant d'être exécutées.

- Sinon afficher les erreurs et remettre les valeurs pertinantes dans les inputs (4 points)

les erreurs sont affichées au cas par cas.
toutes les valeurs sont remises sauf le mot de passe (je ne sais pas si c'est pertinent de mettre l'email, dans le doute je l'ai remis).

- Bonus de 3 points si vous arrivez à envoyer un mail via un compte SMTP de votre choix pour valider l'adresse email en bdd
Ajout "account_activation_hash" dans la table user
La valeur est supprimé quand l'email est validé
