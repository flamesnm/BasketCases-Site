<?php
class DNA_Move_Tab extends DNA_Tab {

	public function __construct( $tab ) {
		parent::__construct( $tab );
		$this->properties = array(
			'clear' => array(
				'title' => __( 'Restore all ACFCP settings to default', ACF_TD ),
				'desc' => __( 'For restore all settings to default, set this field and Submit form.', ACF_TD )
			),
			'delete' => array(
				'title' => __( 'Delete all settings after deactivation', ACF_TD ),
				'desc' => __( 'If this field is checked, than after deactivation all plugin data will removed from database.', ACF_TD )
			),
			'export' => array(
				'title' => __( 'Export ACFCP settings to .ini file', ACF_TD ),
				'desc' => __( 'Click and download the file with all the fields and plugin settings. You can use this file to store the plugin settings and restore them in the future.', ACF_TD )
			),
			'import' => array(
				'title' => __( 'Import ACFCP settings from .ini file', ACF_TD ),
				'desc' => __( 'Upload file with plugin settings and press submit button. All settings will be restored from this file.', ACF_TD )
			)
		);
	}


	public function update_option(){
		$option = '';
		if ( isset( $_POST['deleteSettings'] ) )
			$option = appthemes_clean( $_POST['deleteSettings'] );
		update_option( 'acf_delete', $option );
	}


	public function get_html(){
		global $acf_admin;
		?>
		<table id="dna-settings-table" class="dna-table widefat">
			<thead>
				<tr>
					<th colspan="2"><span><?php _e( 'Export/Import/Clear Settings', ACF_TD ); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 250px;"><label class="titletip" tip="<?php esc_attr_e( $this->properties['clear']['desc'] ); ?>"><?php _e( $this->properties['clear']['title'] ); ?>:</label></td>
					<td><input id="clearSettings" type="checkbox"  name="clearSettings" value="clear"/></td>
				</tr>
				<tr>
					<td style="width: 250px;"><label class="titletip" tip="<?php esc_attr_e( $this->properties['delete']['desc'] ); ?>"><?php _e( $this->properties['delete']['title'] ); ?>:</label></td>
					<td><input id="deleteSettings" type="checkbox" name="deleteSettings" value="delete" <?php checked( get_option( 'acf_delete' ), 'delete', true )?>/></td>
				</tr>
				<tr>
					<td style="width: 250px;"><label class="titletip" tip="<?php esc_attr_e( $this->properties['export']['desc'] ); ?>"><?php _e( $this->properties['export']['title'] ); ?>:</label>
					</td>
					<td><a href="admin.php?page=<?php echo esc_attr( $acf_admin->menu_slug ); ?>&dna_action=export" class="button-secondary"><?php _e( 'Download', ACF_TD ); ?></a></td>
				</tr>
				<tr>
					<td style="width: 250px;"><label class="titletip" tip="<?php esc_attr_e( $this->properties['import']['desc'] ); ?>"><?php _e( $this->properties['import']['title'] ); ?>:</label></td>
					<td><input type="file" name="importSettings" id="importSettings" ACCEPT="ini" /></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th  colspan="2"></th>
				</tr>
			</tfoot>
		</table>
		<?php
	}

}