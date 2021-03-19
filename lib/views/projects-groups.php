<div id="psp-projects">
     <?php
     if( $projects->have_posts() ): ?>

          <div class="psp-archive-body">
               <div class="psp-archive-section">
                    <div class="psp-table-header psp-multi-row">
                         <div class="psp-table-header__heading">
                              <div class="psp-h2"><?php esc_html_e( 'Projects', 'psp_projects' ); ?></div>
                         </div>
                    </div>
               </div>
          </div>

     <?php else: ?>
          <div class="psp-notice">
               <div class="psp-p"><?php esc_html_e( 'This group doesn\'t have any projects', 'psp_projects' ); ?></div>
          </div>
     <?php endif; ?>
</div>
