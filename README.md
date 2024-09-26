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

    APP_ENV=dev
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
DATABASE_URL="mysql://root:@127.0.0.1:3306/entitiesg1?serverVersion=8.0.31&charset=utf8mb4"
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

php bin/console m:mi
php bin/console d:m:m
```

Faire la migration

Vous pouvez migrer vers la DB, et voir le format colle à vos exigences MySQL en regardant la DB

### Création de nos tables

La table `Post` existe déjà, on va créer les tables suivantes, vides par défaut (mise à part l'id)

    php bin/console make:entity Section
    php bin/console make:entity Comment
    php bin/console make:entity Tag

Nous effectuons une nouvelle migration.

On peut voir si on en a besoin avec

    php bin/console doctrine:migrations:diff

Si c'est le cas, il va créer un fichier de migration comme

    php bin/console make:migration

puis

    php bin/console d:m:m

### Remplissage de Section

    php bin/console make:entity Section

```php
# ...
#[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(
        # On souhaite ne pas perdre la moitié
        # des numériques... donc unsigned !
        options: [
            'unsigned' => true,
        ]
    )]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $sectionTitle = null;

    #[ORM\Column(length: 600, nullable: true)]
    private ?string $sectionDescription = null;
# ...
```

### Jointure de Post vers Section

Jointure en `ManyToMany`, le choix de la table mère - enfant est faite lors de la création de la jointure, même si le `manytomany` est en principe `bidirectionel`

On va choisir le `parent` Post

    php bin/console make:entity Post

```bash
php bin/console make:entity Post
 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > sections

 Field type (enter ? to see all types) [string]:
 
 > ManyToMany
ManyToMany

 What class should this entity be related to?:
 > Section
Section

 Do you want to add a new property to Section so that you can access/update Post objects from it - e.g. $section->getPosts()? (yes/no) [yes]:
 >

 A new property will also be added to the Section class so that you can access the related Post objects from it.

 New field name inside Section [posts]:
 >

 updated: src/Entity/Post.php
 updated: src/Entity/Section.php

```

Dans `src/Entity/Post.php`

```php
<?php

namespace App\Entity;

use App\Repository\PostRepository;
# utilisation des ArrayCollection et des Collections
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

# ...

    /**
     * Jointure many to many vers Section. Cet attribut est le parent et 
     * est inversée par l'attribut de Section `posts`.
     Ce many to many est bidirectionnel, pourtant, Post est le responsable de Section 
     * @var Collection<int, Section>
     */
    #[ORM\ManyToMany(targetEntity: Section::class, inversedBy: 'posts')]
    private Collection $sections;

    # un constructeur est créé.
    public function __construct()
    {
        # il nous permet d'initialiser le tableau de type Collection
        # pour éventuelles Sections
        $this->sections = new ArrayCollection();
    }

   # ... getters and setters

    /**
     * Si on veut récupérer les sections depuis le Post
     * @return Collection<int, Section>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    // on veut rajouter des sections au Post actuel (update ou post)
    public function addSection(Section $section): static
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
        }

        return $this;
    }

    // on veut pouvoir supprimer les sections depuis un Post
    public function removeSection(Section $section): static
    {
        $this->sections->removeElement($section);

        return $this;
    }
}

```

Et dans Dans `src/Entity/Section.php`

```php
<?php

#...

    /**
     *  Relation M2M vers Post, mais on voit que le 'parent' mappedBy: est
     *  l'attribut sections se trouvant dans POST, c'est l'enfant
     * @var Collection<int, Post>
     */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'sections')]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

   /*
    * Mêmes méthodes que de POST, mais pour récupérer, ajouter supprimer des
    * post depuis Section
    */
    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->addSection($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            $post->removeSection($this);
        }

        return $this;
    }
}

```

### Création d'un tag en git

    git tag -a v0.1 -m "Post M2M Section"
    git push origin v0.1

[tag v.0.1](https://github.com/WebDevCF2m2023/EntitiesG1/releases/tag/v0.1)

### CRUD de Post et Section

Nous allons les faire dans une nouvelle branche, car nous n'en aurons pas besoin immédiatement :

    

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

### On va compléter les tables présentes

Création des relations et des champs de table :

    php bin/console make:migration

    php bin/console doctrine:migrations:migrate

## Création des 'utilisateurs'

    php bin/console make:user

```bash
$ php bin/console make:user

 The name of the security user class (e.g. User) [User]: >

 Do you want to store user data in the database (via Doctrine)? (yes/no) [yes]:
 >

 Enter a property name that will be the unique "display" name for the user (e.g. email, usernam
e, uuid) [email]:
 > username

 Will this app need to hash/check user passwords? Choose No if passwords are not needed or will
 be checked/hashed by some other system (e.g. a single sign-on server).

 Does this app need to hash/check user passwords? (yes/no) [yes]:
 >

 created: src/Entity/User.php
 created: src/Repository/UserRepository.php
 updated: src/Entity/User.php
 updated: config/packages/security.yaml


  Success!


 Next Steps:
   - Review your new App\Entity\User class.
   - Use make:entity to add more fields to your User entity and then run make:migration.
   - Create a way to authenticate! See https://symfony.com/doc/current/security.html
```

Création d'une entité avec la particularité de permettre les connexions `src/Entity/User.php`. On va la modifier

On peut aussi voir la gestion de la sécurité dans `config/packages/security.yaml`:

```twig
#...
    providers:
        # used to reload user from session & other features (e.g. switch_user)
        app_user_provider:
            entity:
                class: App\Entity\User
                property: username
# ...
```

On modifie nos tables pour pouvoir vérifier la cohérence en MySQL

    php bin/console ma:mi
    php bin/console d:m:m

## Création d'une page de connexion

    php bin/console make:security:form-login

```bash
php bin/console make:security:form-login

 Choose a name for the controller class (e.g. SecurityController) [SecurityController]:
 >

 Do you want to generate a '/logout' URL? (yes/no) [yes]:
 >

 Do you want to generate PHPUnit tests? [Experimental] (yes/no) [no]:
 >

 created: src/Controller/SecurityController.php
 created: templates/security/login.html.twig
 updated: config/packages/security.yaml


  Success!


 Next: Review and adapt the login template: security/login.html.twig to suit your needs.
```

```yaml
# config/packages/security.yaml

# ...
firewalls:
  dev:
    pattern: ^/(_(profiler|wdt)|css|images|js)/
    security: false
  main:
    lazy: true
    provider: app_user_provider
    # notre firewall ouvre une porte pour User
    form_login:
      login_path: app_login
      check_path: app_login
      enable_csrf: true
    logout:
      path: app_logout
# ...
```

Débogage des routes :

    php bin/console de:r

On va remplir la table `user`

Avec le contenu suivant :

- username
  1) adminLee
  2) redacGuy
  3) userEr
- roles ! json
  1) ["ROLE_ADMIN","ROLE_REDAC","ROLE_USER"]
  2) ["ROLE_REDAC","ROLE_USER"]
  3) []
- password : Il va falloir crypter les mots de passes avec
  
  php bin/console security:hash-password

1) 123admin123
2) ad123min
3) adddmin

- user_mail
ici, vous choisissez
- user_real_name
  ici vous choisissez
- user_active
 true

### Ajoutez login/logout au menu

```twig
{# templates/main/menu.html.twig #}
<nav>
    {# on utilise path('nom_du_chemin') lorsqu'on veut un lien vers une page #}
    <a href="{{ path('homepage') }}">Homepage</a>
    <a href="{{ path('about_me') }}">About me</a>
    {# si on est connecté, on affiche la déconnexion (pas de sécurité réelle) #}
    {% if is_granted('IS_AUTHENTICATED') %}
    <a href="{{ path('app_logout') }}">Logout</a>
    {# si pas connecté, lien vers login #}
    {% else %}
    <a href="{{ path('app_login') }}">Login</a>
    {% endif %}
</nav>
```

Ceci n'est que la partie front-end, si on souhaite inactiver la possibilité d'aller sur `/login` si on est connecté, on peut le faire au niveau du contrôleur :

`src/Controller/SecurityController.php`
```php
# ...
#[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // si on est déjà connecté
        if ($this->getUser()) {
            // on retourne sur l'accueil
            return $this->redirectToRoute('homepage');
        }
# ...
```

### On va twigger tout ça !

Recherche de template :

https://startbootstrap.com/template/blog-post#google_vignette

Le dossier se trouve dans `datas`

On va partir de `templates/base.html.twig` pour modifier les entêtes, on utilise la balise `{{ asset('assets/mon/chemin/fichier.jpg') }}`

En effet `AssetMapper` va chercher les fichiers publics dans le dossier `assets`, et le compiler à chaque fois (tant qu'on est en développement)

Exemple pour le fichier `template/base.html.twig`

```twig
{# template/base.html.twig #}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    {# pour un bootstrap responsive #}
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>{% block title %}EntitiesG1{% endblock %}</title>
        <!-- Favicon-->
        {# icone se trouvant dans le dossier `assets` #}
        <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}" />
        {% block stylesheets %}
        {% endblock %}

        {% block javascripts %}
            {# utilisation de ASSETMAPPER #}
            {% block importmap %}{{ importmap('app') }}{% endblock %}
        {% endblock %}
    </head>
    <body>
    {#  notre contenu (nos templates #}
    {% block content %}
    {% endblock %}
    {# contenu automatique des cruds, form etc... #}
    {% block body %}
    {% endblock %}
    </body>
</html>

```

Dans `templates/main/menu.html.twig`

```twig
        {# si nous sommes connectés #}
                {% if is_granted('IS_AUTHENTICATED') %}
                <li class="nav-item"><a class="nav-link" href="{{ path('app_logout') }}">Déconnexion</a></li>
                    {% if is_granted('ROLE_ADMIN') %}
                <li class="nav-item"><a class="nav-link" href="#">Administration</a></li>
                    {% endif %}
                {% else %}
                <li class="nav-item"><a class="nav-link" href="{{ path('app_login') }}">Connexion</a></li>
                {% endif %}
```

## Mise en place ardue du template

Il faut utiliser un nombre de block adéquat pour nos pages de front

### Créer un contrôleur d'administration

  php bin/console make:controller AdminController
  
Une route vers un dossier `admin` a été créée, on va vérifier si un rôle lui est attribué dans le fichier `config/packages/security.yaml`

```yaml
    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        - { path: ^/admin, roles: ROLE_ADMIN }
        # - { path: ^/profile, roles: ROLE_USER }
```

Dorénavant, ce dossier (et sous-dossiers sont accessibles que par les `ROLE_ADMIN`)

https://symfony.com/doc/current/security.html#roles

## Création d'un contrôleur pour Admin

    php bin/console make:controller AdminController

On modifie le fichier pour passer certaines variables :

`src/Controller/AdminController.php`
```php
# ...
#[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'title' => 'Administration',
            'homepage_text' => "Bienvenue {$this->getUser()->getUsername()}",
        ]);
    }
# ...
```

On duplique `templates/template.front.html.twig` en `templates/template.back.html.twig`. On modifiera ce template suivant les besoins.

On modifie `templates/admin/index.html.twig` pour le faire correspondre aux variables du contrôleur

```twig
{% extends 'template.back.html.twig' %}

{% block title %}{{ parent() }} | {{ title }}{% endblock %}

{% block header %}
    <h1>{{ title }}</h1>
    <p>{{ homepage_text }}</p>
{% endblock %}
```

## Modification des menus

Suivant que l'on soit en front `templates/main/_menu.html.twig` ou en back `templates/admin/_menu.admin.html.twig`

## Création du CRUD pour `Section`

```bash
php bin/console make:crud

 The class name of the entity to create CRUD (e.g. AgreeableChef):
 > Section
Section

 Choose a name for your controller class (e.g. SectionController) [SectionController]:
 > AdminSectionController

 Do you want to generate PHPUnit tests? [Experimental] (yes/no) [no]:
 >

 created: src/Controller/AdminSectionController.php
 created: src/Form/SectionType.php
 created: templates/admin_section/_delete_form.html.twig
 created: templates/admin_section/_form.html.twig
 created: templates/admin_section/edit.html.twig
 created: templates/admin_section/index.html.twig
 created: templates/admin_section/new.html.twig
 created: templates/admin_section/show.html.twig


  Success!


 Next: Check your new CRUD by going to /admin/section/
```

On crée les liens dans la page d'accueil et le menu de l'admin vers 

    <a href="{{ path('app_admin_section_index') }}">Crud Section</a>

Ne pas oublier de mettre en commentaire une partie du formulaire `src/Form/SectionType.php`

```php
<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sectionTitle')
            ->add('sectionDescription')
            /*->add('posts', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}

```

## Affichage des sections sur le front

Dans `src/Controller/MainController.php`

```php
# ....
# appel du gestionnaire de section
use App\Repository\SectionRepository;
# ...
// Création de l'url pour le détail d'une section
    #[Route(
        # chemin vers la section avec son id
        path: '/section/{id}',
        # nom du chemin
        name: 'section',
        # accepte l'id au format int positif uniquement
        requirements: ['id' => '\d+'],
        # si absent, donne 1 comme valeur par défaut
        defaults: ['id'=>1])]

    public function section(SectionRepository $sections, int $id): Response
    {
        // récupération de la section
        $section = $sections->find($id);
        return $this->render('main/section.html.twig', [
            'title' => 'Section '.$section->getSectionTitle(),
            'homepage_text'=> $section->getSectionDescription(),
            'section' => $section,
            'sections' => $sections->findAll(),
        ]);
    }
    #...

```

Et dans le menu `templates/main/_menu.html.twig`

```twig
{# on va afficher les liens vers nos section #}
              {% for section in sections %}
                <li class="nav-item"><a class="nav-link" href="{{ path('section',{id:section.id}) }}">{{ section.sectionTitle }}</a></li>
              {% endfor %}
```

### Création du template

```twig
{# templates/main/section.html.twig #}
{% extends 'template.front.html.twig' %}

{# on surcharge le block parent #}
{% block title %}{{ parent() }} | {{ title }}{% endblock %}

{% block header %}
<h1>{{ title }}</h1>
    <p>{{ homepage_text }}</p>
{% endblock %}
```