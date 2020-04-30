<?php
require_once "../../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;

// Handle your POST data here...
if ( isset($_POST['assign'])) {


    $PDOX->queryDie("INSERT INTO {$p}chemtube3d_assigned
        (link_id, user_id, url_id, updated_at)
        VALUES ( :LI, :UI, :URLID, NOW() )
        ON DUPLICATE KEY UPDATE url_id=:URLID, updated_at = NOW()",
        array(
            ':LI' => $LINK->id,
            ':UI' => $USER->id,
            ':URLID' => $_POST["url_id"]
        )
    );

   
    header('Location: '.addSession('index.php'));
    return;
}

// Start of the output
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->flashMessages();

//checm if an assigned link

$assignedsql = "SELECT chemtube3d_url.url FROM {$p}chemtube3d_url 
                INNER JOIN {$p}chemtube3d_assigned ON chemtube3d_url.url_id = chemtube3d_assigned.url_id 
                where link_id = ".$LINK->id;

$assignedrow = $PDOX->rowDie($assignedsql);


if ($USER->instructor) {

    // Retrieve chemtube3d urls
    $rows = $PDOX->allRowsDie("SELECT * FROM {$p}chemtube3d_url WHERE 1");

    //display them in a simple drop down
    echo "<br/><br/>";
    echo '<form method="post">';
    echo '<label for="url_id">Select a Link to Assign:</label>';

    echo '<select class="ct3d-select" name="url_id" id="url_id">';

    foreach ($rows as $url) {
    
    echo '<option value="'.$url['url_id'].'">'.$url['description'].'</option>';
    
    }
    echo '<select id="url_id">';

    echo '<input type="submit" name="assign" class="btn btn-xs btn-success" value="Assign">';
    echo '</form>';
    echo "<br/><br/>";
}


if ($assignedrow) {


    echo '<p><iframe src="https://www.chemtube3d.com/wordpress/'.$assignedrow['url'].'" width="100%" height="800"></iframe></p>';

 

} else {
 echo "There is no assigned URL for this LMS link";

}


/*
//this processes Nicks html index file
$html = file_get_contents('ct3d_index.html');
$doc = new DOMDocument();
$doc->loadHTML($html);

$links = $doc->getElementsByTagName('a');
$extractedLinks = array();

foreach($links as $link){
 
    //Get the link text.
    $linkText = $link->nodeValue;
    echo($linkText."<br/>");
    
    
    //Get the link in the href attribute.
    $linkHref = $link->getAttribute('href');
    
    $linkpieces = explode("/", $linkHref);
    
    $PDOX->queryDie("INSERT INTO {$p}chemtube3d_url
        (url, description)
        VALUES ( :URL, :DES )",
        array(
            ':URL' => $linkpieces[3],
            ':DES' => trim($linkText)
        )
    );
    
     echo($linkpieces[3]."<br/>");
    //If the link is empty, skip it and don't
    //add it to our $extractedLinks array
    if(strlen(trim($linkHref)) == 0){
        continue;
    }
 
   
 
    
 
}
*/

$OUTPUT->footerStart();
?>
<link href="js/chosen/chosen.min.css" rel="stylesheet" />
<script src="js/chosen/chosen.jquery.js"></script>
<script>
  $(document).ready(function () {

        $('.ct3d-select').chosen({width: "100%", include_group_label_in_selected: true});

   });
</script>
<?php
$OUTPUT->footerEnd();

