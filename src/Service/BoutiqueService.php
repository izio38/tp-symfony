<?php
namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;

// Un service pour manipuler le contenu de la Boutique
//  qui est composée de catégories et de produits stockés "en dur"
class BoutiqueService
{
    // renvoie toutes les catégories
    public function findAllCategories()
    {
        return $this->categories;
    }

    // renvoie la categorie dont id == $idCategorie
    public function findCategorieById(int $idCategorie)
    {
        $res = array_filter($this->categories, function ($c) use (
            $idCategorie
        ) {
            return $c["id"] == $idCategorie;
        });
        return sizeof($res) === 1 ? $res[array_key_first($res)] : null;
    }

    // renvoie le produits dont id == $idProduit
    public function findProduitById(int $idProduit)
    {
        $res = array_filter($this->produits, function ($p) use ($idProduit) {
            return $p["id"] == $idProduit;
        });
        return sizeof($res) === 1 ? $res[array_key_first($res)] : null;
    }

    // renvoie tous les produits dont idCategorie == $idCategorie
    public function findProduitsByCategorie(int $idCategorie)
    {
        return array_filter($this->produits, function ($p) use ($idCategorie) {
            return $p["idCategorie"] == $idCategorie;
        });
    }

    // renvoie tous les produits dont libelle ou texte contient $search
    public function findProduitsByLibelleOrTexte(string $search)
    {
        return array_filter($this->produits, function ($p) use ($search) {
            return $search == "" ||
                mb_strpos(
                    mb_strtolower($p["libelle"] . " " . $p["texte"]),
                    mb_strtolower($search)
                ) !== false;
        });
    }

    // constructeur du service : injection des dépendances et tris
    public function __construct(RequestStack $requestStack)
    {
        // Injection du service RequestStack
        //  afin de pouvoir récupérer la "locale" dans la requête en cours
        $this->requestStack = $requestStack;
        // On trie le tableau des catégories selon la locale
        usort($this->categories, function ($c1, $c2) {
            return $this->compareSelonLocale($c1["libelle"], $c2["libelle"]);
        });
        // On trie le tableau des produits de chaque catégorie selon la locale
        usort($this->produits, function ($c1, $c2) {
            return $this->compareSelonLocale($c1["libelle"], $c2["libelle"]);
        });
    }

    ////////////////////////////////////////////////////////////////////////////

    private function compareSelonLocale(string $s1, $s2)
    {
        $collator = new \Collator(
            $this->requestStack->getCurrentRequest()->getLocale()
        );
        return collator_compare($collator, $s1, $s2);
    }

    private $requestStack; // Le service RequestStack qui sera injecté
    // Le catalogue de la boutique, codé en dur dans un tableau associatif
    private $categories = [
        [
            "id" => 1,
            "libelle" => "Fruits",
            "visuel" => "images/fruits.jpg",
            "texte" => "De la passion ou de ton imagination"
        ],
        [
            "id" => 3,
            "libelle" => "Junk Food",
            "visuel" => "images/junk_food.jpg",
            "texte" => "Chère et cancérogène, tu es prévenu(e)"
        ],
        [
            "id" => 2,
            "libelle" => "Légumes",
            "visuel" => "images/legumes.jpg",
            "texte" => "Plus tu en manges, moins tu en es un"
        ]
    ];
    private $produits = [
        [
            "id" => 1,
            "idCategorie" => 1,
            "libelle" => "Pomme",
            "texte" => "Elle est bonne pour la tienne",
            "visuel" => "images/pommes.jpg",
            "prix" => 3.42
        ],
        [
            "id" => 2,
            "idCategorie" => 1,
            "libelle" => "Poire",
            "texte" => "Ici tu n'en es pas une",
            "visuel" => "images/poires.jpg",
            "prix" => 2.11
        ],
        [
            "id" => 3,
            "idCategorie" => 1,
            "libelle" => "Pêche",
            "texte" => "Elle va te la donner",
            "visuel" => "images/peche.jpg",
            "prix" => 2.84
        ],
        [
            "id" => 4,
            "idCategorie" => 2,
            "libelle" => "Carotte",
            "texte" => "C'est bon pour ta vue",
            "visuel" => "images/carottes.jpg",
            "prix" => 2.9
        ],
        [
            "id" => 5,
            "idCategorie" => 2,
            "libelle" => "Tomate",
            "texte" => "Fruit ou Légume ? Légume",
            "visuel" => "images/tomates.jpg",
            "prix" => 1.7
        ],
        [
            "id" => 6,
            "idCategorie" => 2,
            "libelle" => "Chou Romanesco",
            "texte" => "Mange des fractales",
            "visuel" => "images/romanesco.jpg",
            "prix" => 1.81
        ],
        [
            "id" => 7,
            "idCategorie" => 3,
            "libelle" => "Nutella",
            "texte" => "C'est bon, sauf pour ta santé",
            "visuel" => "images/nutella.jpg",
            "prix" => 4.5
        ],
        [
            "id" => 8,
            "idCategorie" => 3,
            "libelle" => "Pizza",
            "texte" => "Y'a pas pire que za",
            "visuel" => "images/pizza.jpg",
            "prix" => 8.25
        ],
        [
            "id" => 9,
            "idCategorie" => 3,
            "libelle" => "Oreo",
            "texte" => "Seulement si tu es un smartphone",
            "visuel" => "images/oreo.jpg",
            "prix" => 2.5
        ]
    ];
}
