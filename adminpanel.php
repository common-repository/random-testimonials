<?php 
if (isset($_POST['addQuote'])) { ?>

    <div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);">
        <p><?php echo __('New client testimonial added successful', 'random-testimonials'); ?></p></div>
<?php } ?>

<?php if (isset($_GET['deleteEntry'])) { ?>

    <div class="updated fade" id="message" style="background-color: rgb(255,0,51);">
        <p><?php echo __('Single client testimonial successfully deleted', 'random-testimonials'); ?></p></div>
<?php } ?>

<?php if ($_POST['bulkaction'] == "Delete") { ?>

    <div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);">
        <p><?php echo __('Client testimonial successfully deleted', 'random-testimonials'); ?></p></div>
<?php } ?>

<?php if ($_POST['bulkaction'] == "Approve") { ?>

    <div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);">
        <p><?php echo __('Client testimonial successfully approved', 'random-testimonials'); ?></p></div>
<?php } ?>

<?php if ($_POST['editEntry']) { ?>

    <div class="updated fade" id="message" style="background-color: rgb(0,255,255);">
        <p><?php echo __('Quote successfully edited', 'random-content-widget'); ?></p></div>
<?php } ?>

<?php

if (isset($_POST['addQuote'])) {
    $query = sprintf("      
      INSERT INTO wp_testimonials   
      SET name = '%s', quote = '%s'",
        $_POST['name'], $_POST['quote']
    );
    $result = mysql_query($query);

}


if (isset($_POST['editEntry'])) {
    global $wpdb;
    $quote = $_POST['quote'];
    $id = $_POST['id'];
    $update = "UPDATE wp_testimonials SET quote='$quote'  WHERE id='$id'";
    $results = $wpdb->query($update);
}

	if (isset($_GET['deleteEntry'])) {
		$id = $_GET['id'];
		mysql_query("DELETE FROM wp_testimonials WHERE id='$id'");
	}

if ($_POST['bulkaction'] == "Delete") {
    foreach ($_POST as $id) {
        mysql_query("DELETE FROM wp_testimonials WHERE id='$id'");
    }

}

if ($_POST['bulkaction'] == "Approve") {
    foreach ($_POST as $id) {
        mysql_query("UPDATE wp_testimonials SET queued=0 WHERE id='$id'");
    }
}

?>

<?php
if (($_GET['action'] == "edit")) {
    global $wpdb;
    $rcw = $wpdb->prefix . "testimonials";
    $oldid = $_GET['oldid'];
    $query = "SELECT * FROM wp_testimonials WHERE id='$oldid'";
    $results = mysql_query($query);
    $count = mysql_num_rows($results);
    while ($data = mysql_fetch_array($results)) {
        $id = $data['id'];
        $quote = $data['quote'];

        ?>


<div class="wrap">

    <h2>Edit An Entry</h2>

            <p>Make changes in the form below to edit a quote.</p>

    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
         <table class="form-table">
             <tbody>
             <tr valign="top">
                 <th scope="row"><label for="quote">Quote:</label></th>
                 <td>
                     <textarea class="large-text code" id="quote" cols="50" rows="3"
                               name="quote"><?php echo $quote; ?></textarea>
                 </td>
             </tr> 
             <tbody>
         </table>
         <p class="submit">
             <input type="hidden" value="editEntry" name="editEntry"/>
             <input type="hidden" value="<?php echo $id; ?>" name="id"/>
             <input type="submit" value="<?php _e('Edit Entry') ?>" class="button-primary" name="Update"/>
         </p>
     </form>    

</div>
                <?php }
} else { ?>

        <div class="wrap">
    <div class="icon32" id="icon-edit-comments"><br/></div>
<?php echo "  <h2>" . __('Random Testimonials', 'random-testimonials') . "</h2>"; ?>
  <br/>
    <a href="http://www.bluelayermedia.com/hosting/"><img
            src="<?php bloginfo('wpurl');?>/wp-content/plugins/random-testimonials/images/hosting-offer.png" border="0"/></a>
    <br/><br/>


<?php echo "  <h3>" . __('Queued testimonials:', 'random-testimonials') . "</h3>"; ?>
<p><?php echo __('This is the list of queued testimonials awaiting your approval.', 'random-testimonials'); ?></p>

    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <table cellspacing="0" class="widefat fixed">
            <thead>
            <tr class="thead">
                <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                <th class="column-username" id="username" scope="col"
                    style="width: 100px;"><?php echo __('Name', 'random-testimonials'); ?></th>
                <th class="column-name" id="name"
                    scope="col"><?php echo __('Testimonial', 'random-testimonials'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr class="thead">
                <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
                <th class="column-username" scope="col"
                    style="width: 100px;"><?php echo __('Name', 'random-testimonials'); ?></th>
                <th class="column-name" scope="col"><?php echo __('Testimonial', 'random-testimonials'); ?></th>
            </tr>
            </tfoot>
        <?php
          $query = sprintf("SELECT id, name, quote FROM wp_testimonials WHERE queued='1'");
        $results = mysql_query($query);
        $count = mysql_num_rows($results);
        while ($row = mysql_fetch_array($results)) {
            ?>

                <tbody class="list:user user-list" id="users">
                <tr class="alternate" id="user-1">
                    <th class="check-column" scope="row">
                        <input type="checkbox" value="<?php echo $row['id']; ?>" class="administrator"
                               id="<?php echo $row['id']; ?>" name="<?php echo $row['id']; ?>"/>
                            <!--          <input type="checkbox" name="<?=$row[id]?>" id="<?=$row[id]?>
                            " value="<?=$row[id]?>" /> -->
                    </th>
                    <td class="username column-username" style="width: 100px;">
                    <?php echo $row['name']; ?>
        </td>
                    <td class="name column-name">
                    <?php echo $row['quote']; ?>
                        <div class="row-actions"><span class='edit'><a
                                href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&amp;action=edit&amp;oldid=<?php echo $row['id']; ?>"
                                title="Edit this quote">Edit</a> | </span><span class='delete'><a class='submitdelete'
                                                                                                 title='Delete this entry'
                                                                                                 href='<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&amp;deleteEntry&amp;id=<?php echo $row['id']; ?>'
                                                                                                 onclick="if ( confirm('You are about to delete this entry \n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>
                        </div>
        </td>
                </tr>
                </tbody>
            <?php

        }
        if ($count < 1) {
            ?>

                <tbody class="list:user user-list" id="users">
                <tr class="alternate" id="user-1">
                    <th class="check-column" scope="row">
                    </th>
                    <td class="name column-name" colspan="2">
                    <?php echo __('There are no testimonials yet', 'random-testimonials'); ?>
        </td>
                </tr>
                </tbody>
            <?php

        };
        ?>
        </table>
        <p class="submit">
            <select name="bulkaction">
                <option>Bulk Actions</option>
                <option>Approve</option>
                <option>Delete</option>
            </select>
            <input type="submit" value="Apply" class="button-primary" name="Submit"/>
        </p>
    </form>

    <hr>

<?php echo "  <h3>" . __('Add new testimonial:', 'random-testimonials') . "</h3>"; ?>
    <p><?php echo __('Fill in the form below to add a new testimonial.', 'random-testimonials'); ?></p>

    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><label for="name"><?php echo __('Client Name', 'wp-client-testimonials'); ?></label>
                </th>
                <td>
                    <input type="text" id="name" name="name" class="regular-text"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="quote"><?php echo __('Testimonial', 'wp-client-testimonials'); ?></label>
                </th>
                <td>
                    <textarea class="large-text code" id="quote" cols="50" rows="3" name="quote"></textarea>
                </td>
            </tr>
            <tbody>
        </table>
        <p class="submit">
            <input type="hidden" value="addQuote" name="addQuote"/>
            <input type="submit" value="Add Quote" class="button-primary" name="Submit"/>
        </p>
    </form>

<hr>
    
<?php echo "  <h3>" . __('List of testimonials:', 'random-testimonials') . "</h3>"; ?>

    <p><?php echo __('This is the list of already established and active testimonials as they are shown random on your website.', 'random-testimonials'); ?></p>

    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <table cellspacing="0" class="widefat fixed">
            <thead>
            <tr class="thead">
                <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                <th class="column-username" id="username" scope="col"
                    style="width: 100px;"><?php echo __('Name', 'random-testimonials'); ?></th>
                <th class="column-name" id="name"
                    scope="col"><?php echo __('Testimonial', 'random-testimonials'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr class="thead">
                <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
                <th class="column-username" scope="col"
                    style="width: 100px;"><?php echo __('Name', 'random-testimonials'); ?></th>
                <th class="column-name" scope="col"><?php echo __('Testimonial', 'random-testimonials'); ?></th>
            </tr>
            </tfoot>
        <?php
          $query = sprintf("SELECT id, name, quote FROM wp_testimonials WHERE queued='0'");
        $results = mysql_query($query);
        $count = mysql_num_rows($results);
        while ($row = mysql_fetch_array($results)) {
            ?>

                <tbody class="list:user user-list" id="users">
                <tr class="alternate" id="user-1">
                    <th class="check-column" scope="row">
                        <input type="checkbox" value="<?php echo $row['id']; ?>" class="administrator"
                               id="<?php echo $row['id']; ?>" name="<?php echo $row['id']; ?>"/>
                            <!--          <input type="checkbox" name="<?=$row[id]?>" id="<?=$row[id]?>
                            " value="<?=$row[id]?>" /> -->
                    </th>
                    <td class="username column-username" style="width: 100px;">
                    <?php echo $row['name']; ?>


                    </td>
                    <td class="name column-name">
                    <?php echo $row['quote']; ?>
                    <div class="row-actions"><span class='edit'><a
                                href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&amp;action=edit&amp;oldid=<?php echo $row['id']; ?>"
                                title="Edit this quote">Edit</a> | </span><span class='delete'><a class='submitdelete'
                                                                                                 title='Delete this entry'
                                                                                                 href='<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&amp;deleteEntry&amp;id=<?php echo $row['id']; ?>'
                                                                                                 onclick="if ( confirm('You are about to delete this entry \n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>
                        </div>
        </td>
                </tr>
                </tbody>
            <?php

        }
        if ($count < 1) {
            ?>

                <tbody class="list:user user-list" id="users">
                <tr class="alternate" id="user-1">
                    <th class="check-column" scope="row">
                    </th>
                    <td class="name column-name" colspan="2">
                    <?php echo __('There are no testimonials yet', 'random-testimonials'); ?>
        </td>
                </tr>
                </tbody>
            <?php

        };
        ?>
          </table>

        <p class="submit">
            <input type="hidden" value="Delete" name="bulkaction"/>
            <input type="submit" value="Remove" class="button-primary" name="Submit"/>
        </p>
    </form>
</div>
  
 <?php } ?> 