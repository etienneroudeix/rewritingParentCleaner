<?php
include_once(__DIR__ . "/../../../fonctions/authplugins.php");

autorisation("rewritingParentCleaner");

include_once(__DIR__ . "/RewritingParentCleaner.class.php");
$rewritingParentCleaner = new RewritingParentCleaner();

if($_REQUEST['action'] == "clean") {
    $rewritingParentCleaner->clean();
}
?>

<div class="row-fluid">
    <div class="span12">
        <h3>Rewriting Parent Cleaner</h3>
    </div>
</div>

<div class="row-fluid">
    <div class="span12 bigtable">
        <table class="span12">
            <thead>
                <tr>
                    <td>Erreur de rubrique dans les URLs produit</td>
                </tr>
            </thead>
            <tbody>
<?php
$productErrorList = $rewritingParentCleaner->listProductErrors();
if(count($productErrorList) > 0) {

    foreach($productErrorList as $productError) {
?>
                <tr>
                    <td <?php if(!$productError['reecriture']->actif) { ?> style="color: blue"<?php } ?>>
                        <strong><?php echo $productError['reecriture']->url; ?></strong>
                        pointe sur la rubrique <strong><?php echo $productError['param']['id_rubrique']; ?></strong>
                        au lieu de la rubrique <strong><?php echo $productError['produit']->rubrique; ?></strong>
                    </td>
                </tr>
<?php
    }
} else {
?>
                <tr>
                    <td>Aucune erreur détectée</td>
                </tr>
<?php
}
?>
            </tbody>
        </table>
    </div>
</div>

<div class="row-fluid">
    <div class="span12 bigtable">
        <table class="span12">
            <thead>
                <tr>
                    <td>Erreur de dossier dans les URLs contenu</td>
                </tr>
            </thead>
            <tbody>
<?php
$contentErrorList = $rewritingParentCleaner->listContentErrors();
if(count($contentErrorList) > 0) {

    foreach($contentErrorList as $contentError) {
?>
                <tr>
                    <td>
                        <strong><?php echo $contentError['reecriture']->url; ?></strong>
                        pointe sur le dossier <strong><?php echo $contentError['param']['id_dossier']; ?></strong>
                        au lieu du dossier <strong><?php echo $contentError['contenu']->dossier; ?></strong>
                    </td>
                </tr>
<?php
    }
} else {
?>
                <tr>
                    <td>Aucune erreur détectée</td>
                </tr>
<?php
}
?>
            </tbody>
        </table>
    </div>
</div>


<div class="row">
    <div class="span12">
        <a class="btn btn-primary" href="module.php?nom=rewritingParentCleaner&action=clean">clean</a>
    </div>
</div>
