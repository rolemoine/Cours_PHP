# Partie 1 du projet final : Pagination et téléversement images

L'utilisateur pourra dès à présent télécharger des images de son ordinateur et les afficher sur une page web

L'interface initiale est la suivante :

![alt text](rendu_index.png)

Il y a 6 images par pages. Ici, la base de donnée ne contient que 7 images, aussi il y aura 2 pages.

Si l'utilisateur souhaite télécharger une image il suffit qu'il clique sur le lien 'ajouter des images' qui le renverra vers le formulaire suivant:

![alt text](rendu_formulaire.png)

Si le téléchargement s'est correctement déroule alors il en sera informé et sa nouvelle image sera affichée en première position étant donné que l'affichage se fait de l'image la plus récente ajoutée à la plus ancienne


![alt text](rendu_correct.png)

Il y a alors mise à jour de la base de donnée : 

![alt text](rendu_bdd.png)

En revenche, si une erreur apparait il aura un message d'ereur avec la cause identifiée.


![alt text](rendu_erreur.png)
