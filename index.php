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

    echo '<select name="url_id" id="url_id">';

    foreach ($rows as $url) {
    
    echo '<option value="'.$url['url_id'].'">'.$url['description'].'</option>';
    
    }
    echo '<select id="url_id">';

    echo '<input type="submit" name="assign" class="btn btn-xs btn-success" value="Assign">';
    echo '</form>';
    echo "<br/><br/>";
}


if ($assignedrow) {
 //file_get_contents($assignedrow['url']);
 
    //echo '<div class="ct3diframe-container">';

    echo '<p><iframe src="https://xserve.ch.liv.ac.uk/~ngreeves/wordpress/'.$assignedrow['url'].'" width="100%" height="800"></iframe></p>';

    echo '<p>This uses the special URL with the modified pages - no header and footer</p>';

    //echo '</div>';
     
 
 

} else {
 echo "There is no assigned URL for this LMS link";

}

$OUTPUT->footerStart();
?>
<script>
// You might put some JavaScript here
</script>
<?php
$OUTPUT->footerEnd();

