# EntitiesG1

Installation de la version lts (Long Term Support) avec la majorité des bibliothèques pour un site web (`--webapp`)

    symfony new MySecondSymfonyC1 --webapp --version=lts

en cas d'oubli de --webapp

    cd MySecondSymfonyC1
    composer require webapp

## Mise à jour des versions de sécurités

    composer update

## Lancement du serveur

    symfony serve -d
ou

    symfony server:start -d

Pour le fermer 

    symfony server:stop

L'adresse est généralement de type https://127.0.0.1:8000

### Création d'un contrôleur

    symfony console make:controller

ou

    php bin/console make:controller

Le nom doit être en PascalCase terminé par Controller, mais Symfony se charge de le corriger en cas d'oubli.

    php bin/console make:controller MainController

    created: src/Controller/MainController.php
    created: templates/home/index.html.twig

On va vérifier la route par défaut

    php bin/console debug:route

#### Modification de la route

```php
// src/Controller/MainController.php

# ...

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'title' => 'Homepage',
            'homepage_text'=> "Nous somme le ".date('d/m/Y \à H:i'),
            
        ]);
    }
# ...
```

Et de la vue (qui peut gérer la variable title contenant Homepage, et d'autres) :

```twig
{# templates/main/index.html.twig #}
{% extends 'base.html.twig' %}

{% block title %}{{ title }}{% endblock %}

{% block body %}
<h1>{{ title }}</h1>
    <p>{{ homepage_text }}</p>
{% endblock %}
```

On peut accéder à l'accueil depuis la racine de notre

https://127.0.0.1:8000

#### Modifications de `MainController`

Pour obtenir 2 pages, homepage et about

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'title' => 'Homepage',
            'homepage_text'=> "Nous somme le ".date('d/m/Y \à H:i'),
        ]);
    }
    #[Route('/about', name: 'about_me')]
    public function aboutMe(): Response
    {
        return $this->render('main/about.html.twig', [
            'title' => 'About me',
            'homepage_text'=> "Et je parle encore de moi !",
        ]);
    }
}

```

#### Modification de base.html.twig

```twig
{# templates/base.html.twig #}
{# ... #}
<title>{% block title %}EntitiesG1{% endblock %}</title>
{# ... #}
```

#### Création de menu.html.twig

Nous utilisons path() pôur les liens vers les noms de routes pour pouvoir les changer à un seul endroit : `src/controller`

templates/main/menu.html.twig

```twig
<nav>
    {# on utilise path('nom_du_chemin') lorsqu'on veut un lien vers une page #}
    <a href="{{ path('homepage') }}">Homepage</a>
    <a href="{{ path('about_me') }}">About me</a>
</nav>
```

#### Modification de index.html.twig

documentation de `parent` :

https://twig.symfony.com/doc/3.x/functions/parent.html


```twig
{% extends 'base.html.twig' %}

{# on surcharge le block parent #}
{% block title %}{{ parent() }} | {{ title }}{% endblock %}


{% block body %}
    <div class="container">
        <h1>{{ title }}</h1>
{# inclusion depuis la racine du projet ! (templates) #}
{% include 'main/menu.html.twig' %}
    </div>

{% endblock %}
```

About est similaire.

## Création de notre `.env.local`

Le fichier `.env` est le fichier de configuration qui est mis sur `git` et donc `github`

C'est pour celà que nous allons le copier sous le nom de `.env.local`

    cp .env .env.local

Ouvrez `.env.local`

Changez cette ligne

    APP_ENV=dev
    APP_SECRET=c6f06c078199d1f00879e1b9c146cddf
en

    APP_ENV=prod
    APP_SECRET=une_autre_clef_secrete_sécurité

si vous retapez  `php bin/console debug:route`

Vous ne trouverez plus que les routes de production

Dans le fichier `.env.local`

Trouvez la ligne de base de données :

```bash
# ne pas oublier de remettre en dev
APP_ENV=dev

# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

Commentez la ligne postgresql et décommentez la ligne mysql

Passez vos paramètres de connexion dans l'ordre

driver://utilisateur:mot_de_passe@ip_serveur:port/nomdelaDB?options

```bash
DATABASE_URL="mysql://root:@127.0.0.1:3306/mysecondesymfonyc1?serverVersion=8.0.31&charset=utf8mb4"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

## Création de la DB

Avec Doctrine, documentation :

https://symfony.com/doc/current/doctrine.html


    php bin/console doctrine:database:create
    # le mode raccourci
    php bin/console d:d:c

La base de donnée devrait être créée si mysql.exe est activé ou Wamp démarré 

## Création d'une entité

Une entité est la représentation objet d'un élément de sauvegarde de données, dans notre cas, en choisissant mysql, il s'agira d'une table

    php bin/console make:entity

```bash
 php bin/console make:entity Post
 # ....
 created: src/Entity/Post.php
 created: src/Repository/PostRepository.php
```

Avec seulement l'id de la classe Post

## Première migration

    php bin/console make:migration

    success :  created: migrations/Version20240911133839.php

puis

    php bin/console doctrine:migrations:migrate

## Ajout de champs à l'entité `Post`

On utilise maker pour ça

    php bin/console make:entity Post

```bash
php bin/console make:entity Post
 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > postTitle

 Field type (enter ? to see all types) [string]:
 >


 Field length [255]:
 > 160

 Can this field be null in the database (nullable) (yes/no) [no]:
 >

 updated: src/Entity/Post.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > postText

 Field type (enter ? to see all types) [string]:
 > text
text

 Can this field be null in the database (nullable) (yes/no) [no]:
 >

 updated: src/Entity/Post.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > postDateCreated

 Field type (enter ? to see all types) [string]:
 > datetime
datetime

 Can this field be null in the database (nullable) (yes/no) [no]:
 >

 updated: src/Entity/Post.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > postDatePublished

 Field type (enter ? to see all types) [string]:
 > datetime
datetime

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes

 updated: src/Entity/Post.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > postIsPublished

 Field type (enter ? to see all types) [string]:
 > boolean
boolean

 Can this field be null in the database (nullable) (yes/no) [no]:
 >

 updated: src/Entity/Post.php
```

Ce qui nous crée le fichier suivant :

```php
// src/Entity/Post.php


<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 160)]
    private ?string $postTitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $postText = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $postDateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $postDatePublished = null;

    #[ORM\Column]
    private ?bool $postIsPublished = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostTitle(): ?string
    {
        return $this->postTitle;
    }

    public function setPostTitle(string $postTitle): static
    {
        $this->postTitle = $postTitle;

        return $this;
    }

    public function getPostText(): ?string
    {
        return $this->postText;
    }

    public function setPostText(string $postText): static
    {
        $this->postText = $postText;

        return $this;
    }

    public function getPostDateCreated(): ?\DateTimeInterface
    {
        return $this->postDateCreated;
    }

    public function setPostDateCreated(\DateTimeInterface $postDateCreated): static
    {
        $this->postDateCreated = $postDateCreated;

        return $this;
    }

    public function getPostDatePublished(): ?\DateTimeInterface
    {
        return $this->postDatePublished;
    }

    public function setPostDatePublished(?\DateTimeInterface $postDatePublished): static
    {
        $this->postDatePublished = $postDatePublished;

        return $this;
    }

    public function isPostIsPublished(): ?bool
    {
        return $this->postIsPublished;
    }

    public function setPostIsPublished(bool $postIsPublished): static
    {
        $this->postIsPublished = $postIsPublished;

        return $this;
    }
}


```

## Deuxième migration

    php bin/console make:migration

ou

    php bin/console m:mi

puis

    php bin/console doctrine:migrations:migrate

ou

    php bin/console d:m:m

### On veut adapter la table à MySQL

La documentation sur les colonnes (champs) dans `Doctrine` :

https://www.doctrine-project.org/projects/doctrine-orm/en/3.2/reference/attributes-reference.html#attrref_column

```php
// src/Entity/Article.php

#...

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(
        options:[
            "unsigned" => true,
        ]
    )]
    private ?int $id = null;

    #[ORM\Column(
        length: 160
    )]
    private ?string $title = null;

    #[ORM\Column(
        type: Types::TEXT,
    )]
    private ?string $text = null;

    #[ORM\Column(
        type: Types::DATETIME_MUTABLE,
        nullable: true,
        options: [
            'default' => 'CURRENT_TIMESTAMP',
        ]
    )]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(
        type: Types::DATETIME_MUTABLE,
        nullable: true
    )]
    private ?\DateTimeInterface $date_published = null;

    #[ORM\Column(
        nullable: true,
        options: [
            'default' => false,
        ]
    )]
    private ?bool $published = null;
    
# Getters and setter

```

Vous pouvez migrer vers la DB, et voir le format colle à vos exigences MySQL en regardant la DB

## Création du `CRUD` de `Article`

    php bin/console make:crud

Génère les fichiers de CRUD, vues et tests (si choisis)

    created: src/Controller/AdminArticleController.php
    created: src/Form/ArticleType.php
    created: templates/admin_article/_delete_form.html.twig
    created: templates/admin_article/_form.html.twig
    created: templates/admin_article/edit.html.twig
    created: templates/admin_article/index.html.twig
    created: templates/admin_article/new.html.twig
    created: templates/admin_article/show.html.twig
    created: tests/Controller/ArticleControllerTest.php

On a rajouté le chemin dans le `menu.html.twig`

On l'a trouvé avec `php bin/console debug:route`

```twig
<nav>
    {# on utilise path('nom_du_chemin') lorsqu'on veut un lien vers une page #}
    <a href="{{ path('homepage') }}">Homepage</a>
    <a href="{{ path('about_me') }}">About me</a>
    <a href="{{ path('app_admin_article_index') }}">CRUD Article</a>
</nav>
```

On peut mettre l'include dans `templates/base.html.twig`, pour éviter de devoir le faire sur toutes les pages (on le retire de l'index et about)

```twig
 <body>
        {% block nav %}
            {% include 'home/menu.html.twig' %}
        {% endblock %}
        {% block body %}{% endblock %}
    </body>
```

### Mise en forme des formulaires et des pages avec `bootstrap`

Nous allons utiliser les assets qui se trouvent dans le dossier `assets`

Documentation :

Différence AssetMapper et Webpack Encore : https://symfony.com/doc/6.4/frontend.html#using-php-twig

### `AssetMapper`

Documentation : https://symfony.com/doc/6.4/frontend/asset_mapper.html

On va importer bootstrap

    php bin/console importmap:require bootstrap

    [OK] 3 new items (bootstrap, @popperjs/core, bootstrap/dist/css/bootstrap.min.css) added to the importmap.php!

La mise à jour a été effectuée uniquement dans `importmap.php`

Pour tester, on va d'abord trouver les templates `bootstrap` à cette adresse : https://symfony.com/doc/current/form/form_themes.html

Donc pour les formulaires `bootstrap`

```yaml
# config/packages/twig.yaml
twig:
form_themes: ['bootstrap_5_horizontal_layout.html.twig']
# ...
```

Le code `bootstrap` est généré, mais il manque le style !

dans `assets/app.js` on ajoute le lien vers le `css`

```js
import './vendor/bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';
```
Et nos formulaires sont jolis !

On peut utiliser toutes les classes de `bootstrap`

## Manipulation des formulaires