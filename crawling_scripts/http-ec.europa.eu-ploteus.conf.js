ec_site_name = 'EAC-EQF';
ec_protocol = 'http';
ec_domain = 'ec.europa.eu';
ec_base_path = '/ploteus';
ec_base_url = ec_protocol + '://' + ec_domain + ec_base_path;

ec_save_url_filepath  = './state/' + ec_protocol + "-" + ec_domain + "-";
ec_save_url_filepath += ec_base_path.replace(/^\//, "").replace(/\//g, "-") + ".urls.json";

// Cookie header (which is systematically forced)
ec_cookie_header = 'language=en; has_js=1';

// sometimes, fetching a few extra resources is better than missing content or
// generating JavaScript errors.
ec_external_resources_whitelist = {
	// any value other than undefined is fine
	'http://ec.europa.eu/wel/socialbookmark/share.js': 1,
};

// skip_urls_regexps = [ /field_project_sector.*=field_project_sector/ ];
max_url_length = 130
