	<style>
    .form-table th {
    padding-left: 0px;
    }
    
    .boxit_tag{
    position: absolute;
    top: 135px;
    left: 94px;
    font-size: 15px;
    }
    </style>
    <h3>Test an IP</h3>

    <form method="post" action="" name="testform">
    		<label for "testvalue">Enter an IP to test</label>
    		<?php $test = isset($_REQUEST['test_ip']) ? $_REQUEST['test_ip'] : ''; ?>
    		<input type=text value="<?php echo $test; ?>" name="test_ip" />
	      <input type="submit" name="submit" style="width:60px; margin-top:5px;" class="button button-primary" value="Test" />
				<input type="hidden" value="<?php echo wp_create_nonce( TL_SETTING_BLOCK_IP ); ?>" name="_wpnonce"/>
				<input type="hidden" value="test" name="action" />
		</form>

    <h3>Block these IP's</h3>
    <form method="post" action="" name="settingsform">
    <table class="form-table">
    	<tr>
    		<th scope="row">Addresses to block</th>
    		<td>
            	<textarea rows="5" cols="60" type="text" name="<?php echo TL_SETTING_BLOCK_IP;?>" id="tl_ip_address"><?php echo get_option(TL_SETTING_BLOCK_IP);?></textarea>
				<p class="description">Comma seperated IP range or individual IP needs to block. ex:  1.6.0.0 - 1.7.255.255,1.8.0.0,1.8.0.1. One per line is ok  </p>
			</td>
		</tr>
		<tr>
			<th scope="row">Write Message:</th>
			<td>
            	<textarea name="<?php echo TL_SETTING_BLOCK_MESSAGE;?>" id="message" rows="5" cols="60" ><?php echo get_option(TL_SETTING_BLOCK_MESSAGE);?></textarea>
				<p class="description">Write down message to display blocked IP's.</p>
            </td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
        <input type="submit" name="submit" style="width:60px; margin-top:5px;" class="button button-primary" value="Save" />
				<input type="hidden" value="<?php echo wp_create_nonce( TL_SETTING_BLOCK_IP ); ?>" name="_wpnonce" id="hide" />
				<input type="hidden" value="update" name="action" />
			</td>
		</tr>	
	</table>	
	</form>