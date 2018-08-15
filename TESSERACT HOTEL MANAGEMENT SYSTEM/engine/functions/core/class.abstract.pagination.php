<?php
/**
*	@author Clinton Nzedimma, Paul Princewill (c) Novacom Webs Nigeria 2018
*	@package  tesseract Hotel Management System v 1.0.0
*	@subpackage Paging
*   @abstract  This is an abstract class used for pagination
*/



 abstract class Pagination
 {		
			
	public $get_page_num; // url get value
	public $get_num_result_per_page;  //  result per page
	public  $get_search_num_pages; // number of pages
	public $div_container_id;	
	public $file_name_of_page;
 	public $number_of_pages;

 	function __construct()
 	{
 		$this->div_container_id="#";
 	}

protected function display_pagination_links($number_of_pages) {
	/**
	* @param $number_of_pages is the number of pages
	*/
		echo "<br>
		<div class='pagination' align='center'>";
			if($this->get_page_num>1) {
					// if the page number is greater than 1, it displays a link used to go to a page by a step back
					$previous_page_num=$this->get_page_num-1;
					echo 
"	<a href='".$this->file_name_of_page."p=".$previous_page_num. "#".$this->div_container_id."' name='".$this->div_container_id."'> &#9001; </a> 
 ";

}
			
				for ($page=1; $page<=$number_of_pages; $page++) { 
					// page number links here
					echo 
"	<a href='".$this->file_name_of_page."p=".$page. "#".$this->div_container_id."' ".activeLinkSelectorPaginate('p', $page, 'active-page') ."> $page </a> 
";	
}	

				if($number_of_pages >$this->get_page_num) {
					// number of pages is greater than current page number 
					$next_page_num=$this->get_page_num+1;
					echo 
"	<a href='".$this->file_name_of_page."p=".$next_page_num. "#".$this->div_container_id."'> &#9002; </a> 
 ";


 	echo "</div>";
}	
}
 	
 }

?>