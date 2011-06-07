<?php slot('title'); ?>Accueil<?php end_slot() ?>
<h1>
  Bienvenue sur le site de <b>votre</b> emploi du temps.
</h1>


<h2>Les emplois du temps sélectionnés</h2>


<ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix"> 
<?php foreach($categories as $categorie): ?>
  <li><?php echo image_tag("logos/".$categorie->getLogo(), "alt='logo ".$categorie."'");
   echo " ".link_to($categorie, '@categorie_index?categorie='.$categorie->getUrl()) ?>
    <ul>
    <?php foreach($categorie->getPromotions() as $promotion): ?>
      <li class='liste-index-li ' >
        <?php echo link_to($categorie."/".$promotion, "@image?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=", "id='li-id-".$promotion->getId()."'") ?>
      </li>
    <?php endforeach ?>
    </ul>
  </li>
<?php endforeach ?>
</ul>


<!--<ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix"> 
        <li class="ui-widget-content ui-corner-tr"> Text</li> 
        <li class="ui-widget-content ui-corner-tr"> Text</li> 
        <li class="ui-widget-content ui-corner-tr"> Text</li> 
        <li class="ui-widget-content ui-corner-tr"> Text</li> 

</ul> -->
 

<script type="text/javascript">
$(function() {
  // there's the gallery and the trash
  var $gallery = $( "#gallery" ),
          $myedt = $( "#myedt" ),
          $trash = $( "#myedt-trash" );

  // let the gallery items be draggable
  $( ".liste-index-li", $gallery ).draggable({
          cancel: "a.ui-icon", // clicking an icon won't initiate dragging
          revert: "invalid", // when not dropped, the item will revert back to its initial position
          helper: "clone",
          cursor: "move"
  });

  // let the trash be droppable, accepting the gallery items
  $myedt.droppable({
          accept: "#gallery .liste-index-li",
          activeClass: "ui-state-highlight",
          drop: function( event, ui ) {
                  addEdt( ui.draggable );
          }
  });

  // let the gallery be droppable as well, accepting items from the trash
  $trash.droppable({
          accept: "#myedt .liste-index-li",
          activeClass: "ui-state-highlight",
          drop: function( event, ui ) {
                  deleteEdt( ui.draggable );
          }
  });

  // image deletion function
  function addEdt( $item ) {
    var $list = $( "ul", $myedt ).length ?
            $( "ul", $myedt ) :
            $( "<ul/>" ).appendTo( $myedt );

    $item.appendTo( $list ).fadeIn();
  }

  // image recycle function
  var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a>";
  function deleteEdt( $item ) {
          $item.fadeOut();
  }


$( "#myedt" ).sortable();
});
</script>