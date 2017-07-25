<zcmscontent>
	<type>static</type>
	<refresh>0</refresh>
  <header><span>Install and Configure Zfire</span></header>
  <body>
	<div>
 		<p>These are the requirements to run Zfire CMS:</p>
		<ul>
			<li>A web host running PHP 5.6.30 or higher (lower may work; try it and let us know!)</li>
			<li>That was it, actually.</li>
		</ul>
		<p>Perform the following to install Zfire CMS on your site:</p>
		<ul>
			<li>Download the Zfire package from GitHub.</li>
			<li>Uncompress it on your web host.</li>
			<li>You are now running a mirror of this site.</li>
		</ul>
		<p>And now configure your copy of Zfire CMS.</p>
		<ul>
			<li>Individual content boxes are in the <em>zcms_content</em> directory and are simple HTML and PHP.</li>
			<li>Content boxes are called with <em>zcmsGetContent</em> calls in the files in <em>zcms_pagesmain</em> and <em>zcms_pagesextra</em></li>
			<li>The CSS and footer are located in <em>zcms_infrastructure</em></li>
			<li>An extendable library of render-time functions are located in <em>zcms_libraries</em></li>
			<li>If something is cached and you want it refreshed, eliminate it from <em>zcms_contentcache</em></li>
			<li>Consider renaming <em>gen4.php</em> and alter the reference to it in <em>zcms_regen.php</em> in order to prevent direct site refresh by end users.  You can also call that URL with <em>&amp;force=1</em> to make it refresh pages before their normal expiration.</li>
		</ul>
		<p>We plan to enhance this over time.  Feel free to contact us with questions or suggestions.  If you extend Zfire CMS and would like to contribute your enhancements, please contact us!  Thanks for checking out Zfire CMS.</p>
	</div>
  </body>
</zcmscontent>

