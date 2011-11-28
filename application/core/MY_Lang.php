<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// Originaly CodeIgniter i18n library by Jérôme Jaglale
// http://maestric.com/en/doc/php/codeigniter_i18n
//modification by Yeb Reitsma

/* 
in case you use it with the HMVC modular extention
uncomment this and remove the other lines
load the MX_Loader class */

//require APPPATH."third_party/MX/Lang.php";

//class MY_Lang extends MX_Lang {
	
class MY_Lang extends CI_Lang {


    /**************************************************
     configuration
    ***************************************************/
   
    // languages
    // array(lang_url => lang_file_locate)
	private $languages = array(
        'en' => 'en', // en-us
        'tc' => 'tc', // zh-hk
        'sc' => 'sc', // zh-cn
    );
   
	private $language_ids = array(
		'en' => '1',
		'tc' => '2',
		'sc' => '4'
	);
   
    // special URIs (not localized)
    private $special = array (
        "admin"
    );
    
    // where to redirect if no language in URI
    private $uri;
    private $default_uri;
    private $lang_code;
   
    /**************************************************/
    
    
    function MY_Lang()
    {
        parent::__construct();
        
        global $CFG;
        global $URI;
        global $RTR;
        
        $this->uri = $URI->uri_string();
        $this->default_uri = $RTR->default_controller;
        
        $uri_segment = $this->get_uri_lang($this->uri);
        $this->lang_code = $uri_segment['lang'] ;
        
        $url_ok = false;
		
		// locate language file
        if ((!empty($this->lang_code)) && (array_key_exists($this->lang_code, $this->languages)))
        {
            $language = $this->languages[$this->lang_code];
            $CFG->set_item('language', $language);
            $url_ok = true;
        }
        
   		if ((!$url_ok) && (!$this->is_special($uri_segment['parts'][0]))) // special URI -> no redirect
   		{
   			// set default language
   			$CFG->set_item('language', $this->languages[$this->default_lang()]);
   			
   			$uri = (!empty($this->uri)) ? $this->uri: $this->default_uri;
   	     	$uri = ($uri[0] != '/') ? '/'.$uri : $uri;
   			$new_url = $CFG->config['base_url'].$this->default_lang().$uri;
   			
   			header("Location: " . $new_url, TRUE, 302);
   			exit;
   		}
   	}

    
    
    // get current language
    // ex: return 'en' if language in CI config is 'english' 
    function lang()
    {
        global $CFG;        
        $language = $CFG->item('language');
        
        $lang = array_search($language, $this->languages);
        if ($lang)
        {
            return $lang;
        }
        
        return NULL;    // this should not happen
    }
    
    function lang_id()
	{
		global $CFG;
		$language = $CFG->item('language');
		
		$lang_id = $this->language_ids[$language];
		if ($lang_id)
		{
			return $lang_id;
		}
		
		return NULL;
	}
	
    function is_special($lang_code)
    {
        if ((!empty($lang_code)) && (in_array($lang_code, $this->special)))
            return TRUE;
       	else
            return FALSE;
    }
   
   
    function switch_uri($lang)
    {
        if ((!empty($this->uri)) && (array_key_exists($lang, $this->languages)))
        {
        	if ($uri_segment = $this->get_uri_lang($this->uri))
        	{
        		$uri_segment['parts'][0] = $lang;
        		$uri = implode('/',$uri_segment['parts']);
        	}
        	else
        	{
        		$uri = $lang.'/'.$this->uri;
        	}
        }

        return $uri;
    }
    
	//check if the language exists
	//when true returns an array with lang abbreviation + rest
   	function get_uri_lang($uri = '')
   	{
   		if (!empty($uri))
   		{
   			$uri = ($uri[0] == '/') ? substr($uri, 1): $uri;
   			
   			$uri_expl = explode('/', $uri, 2);
   			$uri_segment['lang'] = NULL;
   			$uri_segment['parts'] = $uri_expl;		
   			
   			if (array_key_exists($uri_expl[0], $this->languages))
   			{
   				$uri_segment['lang'] = $uri_expl[0];
   			}
   			return $uri_segment;
   		}
   		else
   			return FALSE;
   	}

   	
    // default language: first element of $this->languages
     function default_lang()
	{
		// $browser_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ',') : '';
		// $browser_lang = substr($browser_lang, 0,2);
		// return (array_key_exists($browser_lang, $this->languages)) ? $browser_lang: DEFAULT_LANG_CODE;
		return DEFAULT_LANG_CODE;
	}
    
    
    // add language segment to $uri (if appropriate)
    function localized($uri)
    {
    	if (!empty($uri))
    	{
    		$uri_segment = $this->get_uri_lang($uri);
    		if (!$uri_segment['lang'])
    		{

    			if ((!$this->is_special($uri_segment['parts'][0])) && (!preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $uri)))
   				{
   	            	$uri = $this->lang() . '/' . $uri;
   	            }
   	        }
   		}
		
        return $uri;
    }
} 

// END MY_Lang Class

/* End of file MY_Config.php */
/* Location: ./system/application/core/MY_Lang.php */