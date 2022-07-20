<div class="custom_field_box">
	<style scoped>
        .custom_field_box {
            display: grid;
            grid-template-columns: 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .custom_field {
            display: contents;
        }
	</style>
	<p class="meta-options custom_field">
        <textarea id="custom_field_citacion"
                  type="text"
                  name="custom_field_citacion"
                  rows="5"
        ><?php echo esc_attr( get_post_meta( get_the_ID(), 'custom_field_citacion', true ) ); ?></textarea>
	</p>
</div>