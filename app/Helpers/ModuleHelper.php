<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ModuleHelper
{
	
	public static function jsonDecode(string $args) 
    {
		$dec = strtr( $args , '123456poligamI', 'poligamI123456');
		$dec = base64_decode( $dec );
		$obj = json_decode( $dec ,true);
		return $obj;
    }

    public static function jsonEncode(array $args) 
	{
		$str = json_encode( $args );
		$enc = base64_encode($str );
		$enc = strtr( $enc, 'poligamI123456', '123456poligamI');
		return $enc;
	}
	
	public static function toJavascript($forms, $app, $class)
	{
		$f = '';
		foreach($forms as $form){
			if($form['view'] != 0){			
				if(preg_match('/(select)/',$form['type'])){
					if($form['option']['opt_type'] == 'external'){
						$table 	=  $form['option']['lookup_table'] ;
						$val 	=  $form['option']['lookup_value'];
						$key 	=  $form['option']['lookup_key'];
						$lookey = '';
						if($form['option']['is_dependency']) $lookey .= $form['option']['lookup_dependency_key'] ;
						$f .= self::createPreCombo( $form['field'] , $table , $key , $val ,$app, $class , $lookey  );
					}		
				}
			}	
		}
		return $f;	
	}

	public static function toForm($forms,$layout)
	{
		$f = ''; 
		$block = $layout['column'];
		$format = $layout['format'];
		$display = $layout['display'];
		$title = explode(",",$layout['title']);
		
		if($format =='tab')
		{
			$f .='<ul class="nav nav-tabs">';
			
			for($i=0;$i<$block;$i++)
			{
				$active = ($i==0 ? 'active' : '');
				$tit = (isset($title[$i]) ? $title[$i] : 'None');	
				$f .= '<li class="'.$active.'"><a href="#'.trim(str_replace(" ","",$tit)).'" data-toggle="tab">'.$tit.'</a></li>
				';	
			}
			$f .= '</ul>';		
		}

		if($format =='tab') $f .= '<div class="tab-content">';
		for($i=0;$i<$block;$i++)
		{		
			if($block == 4) {
				$class = 'col-md-3';
			}  elseif( $block ==3 ) {
				$class = 'col-md-4';
			}  elseif( $block ==2 ) {
				$class = 'col-md-6';
			} else {
				$class = 'col-md-12';
			}	
			
			$tit = (isset($title[$i]) ? $title[$i] : 'None');	
			// Grid format 
			if($format == 'grid')
			{
				$f .= '<div class="'.$class.'">
						<fieldset><legend> '.$tit.'</legend>
				';
			} else {
				$active = ($i==0 ? 'active' : '');
				$f .= '<div class="tab-pane m-t '.$active.'" id="'.trim(str_replace(" ","",$tit)).'"> 
				';			
			}	
			
			
			
			$group = array(); 
			
			foreach($forms as $form)
			{
				$tooltip =''; $required = ($form['required'] != '0' ? '<span class="asterix"> * </span>' : '');
				if($form['view'] != 0)
				{
					if($form['field'] !='entry_by')
					{
						if(isset($form['option']['tooltip']) && $form['option']['tooltip'] !='') 	
						$tooltip = '<a href="#" data-toggle="tooltip" placement="left" class="tips" title="'. $form['option']['tooltip'] .'"><i class="icon-question2"></i></a>';	
						$hidethis = ""; if($form['type'] =='hidden') $hidethis ='hidethis';
						$inhide = ''; if(count($group) >1) $inhide ='inhide';
						//$ebutton = ($form['type'] =='radio' || $form['option'] =='checkbox') ? "ebutton-radio" : "";
						$show = '';
						if($form['type'] =='hidden') $show = 'style="display:none;"';	
						if(isset($form['limited']) && $form['limited'] !='')
						{
							$limited_start = 
							'
				<?php 
				$limited = isset($fields[\''.$form['field'].'\'][\'limited\']) ? $fields[\''.$form['field'].'\'][\'limited\'] :\'\';
				if(SiteHelpers::filterColumn($limited )) { ?>
							';
							$limited_end = '
				<?php } ?>'; 
						} else {
							$limited_start = '';
							$limited_end = ''; 
						}

						if($form['form_group'] == $i)
						{	
							if($display == 'horizontal')
							{			
								if($form['type'] =='hidden')
								{
									$f .= self::formShow($form['type'],$form['field'],$form['required'],$form['option']);
								} else {
									$f .= $limited_start;
									$f .= '					
									  <div class="form-group '.$hidethis.' '.$inhide.'" '.$show .'>
										<label for="'.$form['label'].'" class=" control-label col-md-4 text-left"> '.$form['label'].' '.$required.'</label>
										<div class="col-md-7">
										  '.self::formShow($form['type'],$form['field'],$form['required'],$form['option']).' 
										 </div> 
										 <div class="col-md-1">
										 	'.$tooltip.'
										 </div>
									  </div> '; 
									  $f .= $limited_end;
								}

							} else {
								
								if($form['type'] =='hidden')
								{	
									$f .= self::formShow($form['type'],$form['field'],$form['required'],$form['option']);
								} else {
									$f .= $limited_start;
									$f .= '					
									  <div class="form-group '.$hidethis.' '.$inhide.'" '.$show .'>
										<label for="ipt" class=" control-label "> '.$form['label'].'  '.$required.' '.$tooltip.' </label>									
										  '.self::formShow($form['type'],$form['field'],$form['required'],$form['option']).' 						
									  </div> '; 
									 $f .= $limited_end; 									
								}
 							
							
							}	  
						}	  
					}	  
					
				}					
			}
			if($format == 'grid') $f .='</fieldset>';
			$f .= '
			</div>
			
			';
		} 		
		
		//echo '<pre>'; print_r($f);echo '</pre>'; exit;
		return $f;
	
	}

	public static function formShow( $type , $field , $required ,$option = [])
    {
		$mandatory = '';$attribute = ''; $extend_class ='';
		if(isset($option['attribute']) && $option['attribute'] !='') {
				$attribute = $option['attribute']; }
		if(isset($option['extend_class']) && $option['extend_class'] !='') {
			$extend_class = $option['extend_class']; 
		}				
				
		$show = '';
		if($type =='hidden') $show = 'style="display:none;"';	
				
		if($required =='required') {
			$mandatory = "'required'=>'true'";
		} else if($required =='email') {
			$mandatory = "'required'=>'true', 'parsley-type'=>'email' ";
		} else if($required =='url') {
			$mandatory = "'required'=>'true', 'parsley-type'=>'url' ";
		} else if($required =='date') {
			$mandatory = "'required'=>'true', 'parsley-type'=>'dateIso' ";
		} else if($required =='numeric') {
			$mandatory = "'required'=>'true', 'parsley-type'=>'number' ";
		} else {
			$mandatory = '';
		}		
		
		switch($type)
		{
			default;
				if($required !='0') { $mandatory = 'required'; }
				if(isset($option['prefix']) && $option['prefix'] !='' or isset($option['sufix']) && $option['sufix'] !='')
				{
					$form ='<div class="input-group">';
					if($option['prefix'] !='')
						$form .= ' <span class="input-group-addon">'.$option['prefix'].'</span>';
					
						$form .= "<input  type='text' name='{$field}' id='{$field}' value='{{ \$row['{$field}'] }}' 
						{$mandatory}  {$attribute}   class='form-control {$extend_class}' />";

					if($option['sufix'] !='')
						$form .= ' <span class="input-group-addon">'.$option['sufix'].'</span>';

	                $form .= '</div>';

				} else {
					$form = "<input  type='text' name='{$field}' id='{$field}' value='{{ \$row['{$field}'] }}' 
						{$mandatory}  {$attribute}   class='form-control {$extend_class}' />";
				}
				
				break;
				
			case 'hidden';
				$form = "{!! Form::hidden('{$field}', \$row['{$field}']) !!}";
				break;

			case 'textarea';
				if($required !='0') { $mandatory = 'required'; }
				$form = "<textarea name='{$field}' rows='5' id='{$field}' class='form-control {$extend_class}'  
				         {$mandatory} {$attribute} >{{ \$row['{$field}'] }}</textarea>";
				break;

			case 'textarea_editor';
				if($required !='0') { $mandatory = 'required'; }
				$form = "<textarea name='{$field}' rows='5' id='editor' class='form-control editor {$extend_class}'  
						{$mandatory}{$attribute} >{{ \$row['{$field}'] }}</textarea>";
				break;				
				

			case 'text_date';
				$form = "
				<div class=\"input-group m-b\" style=\"width:150px !important;\">
					{!! Form::text('{$field}', \$row['{$field}'],array('class'=>'form-control date','id'=>'{$field}')) !!}
					<span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
				</div>";
				break;
				
			case 'text_time';
				$form = "
					<div class=\"input-group m-b\" style=\"width:150px !important;\">
						<input  type='text' name='{$field}' id='{$field}' value='{{ \$row['{$field}'] }}' 
						{$mandatory}  {$attribute}   class='form-control {$extend_class}'
						data-date-format='yyyy-mm-dd'
						 />
						 <span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
						 </div>
						 ";
				break;				

			case 'text_datetime';
				if($required !='0') { $mandatory = 'required'; }
				$form = "
				<div class=\"input-group m-b\" style=\"width:150px !important;\">
					{!! Form::text('{$field}', \$row['{$field}'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
				</div>
				";
				break;				

			case 'select';
				if($required !='0') { $mandatory = 'required'; }
				if($option['opt_type'] =='datalist')
				{
					$optList ='';
					$opt = explode("|",$option['lookup_query']);
					for($i=0; $i<count($opt);$i++) 
					{							
						$row =  explode(":",$opt[$i]);
						for($i=0; $i<count($opt);$i++) 
						{					
							
							$row =  explode(":",$opt[$i]);
							$optList .= " '".trim($row[0])."' => '".trim($row[1])."' , ";
							
						}							
					}	
					$form  = "
					<?php \$".$field." = explode(',',\$row['".$field."']);
					";
					$form  .= 
					"\$".$field."_opt = array(".$optList."); ?>
					";	
					
					if(isset($option['select_multiple']) && $option['select_multiple'] ==1)
					{
					 
						$form  .= "<select name='{$field}[]' rows='5' {$mandatory} multiple  class='select2 '  > ";
						$form  .= "
						<?php 
						foreach(\$".$field."_opt as \$key=>\$val)
						{
							echo \"<option  value ='\$key' \".(in_array(\$key,\$".$field.") ? \" selected='selected' \" : '' ).\">\$val</option>\"; 						
						}						
						?>";
						$form .= "</select>";
					} else {
						
						$form  .= "<select name='{$field}' rows='5' {$mandatory}  class='select2 ' id='{$field}'  > ";
						$form  .= "
						<?php 
						foreach(\$".$field."_opt as \$key=>\$val)
						{
							echo \"<option  value ='\$key' \".(\$row['".$field."'] == \$key ? \" selected='selected' \" : '' ).\">\$val</option>\"; 						
						}						
						?>";
						$form .= "</select>";				
					
					}
					
				} else {
					
					if(isset($option['select_multiple']) && $option['select_multiple'] ==1)
					{
						$named ="name='{$field}[]' multiple";
					} else {
						$named ="name='{$field}'";

					}
					$form = "<select ".$named." rows='5' id='{$field}' class='select2 {$extend_class}' {$mandatory} {$attribute} ></select>";


				}
				break;	
				
			case 'file';
				if($required !='0') { $mandatory = 'required'; }

				if(isset($option['image_multiple']) && $option['image_multiple'] ==1)
				{
					$form = '
					<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles(\''.$field.'\')"><i class="fa fa-plus"></i></a>
					<div class="'.$field.'Upl">	
					 	<input  type=\'file\' name=\''.$field.'[]\'  />			
					</div>
					<ul class="uploadedLists " >
					<?php $cr= 0; 
					$row[\''.$field.'\'] = explode(",",$row[\''.$field.'\']);
					?>
					@foreach($row[\''.$field.'\'] as $files)
						@if(file_exists(\'.'.$option['path_to_upload'].'\'.$files) && $files !=\'\')
						<li id="cr-<?php echo $cr;?>" class="">							
							<a href="{{ url(\''.$option['path_to_upload'].'/\'.$files) }}" target="_blank" >{{ $files }}</a> 
							<span class="pull-right removeMultiFiles" rel="cr-<?php echo $cr;?>" url="'.$option['path_to_upload'].'{{$files}}">
							<i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
							<input type="hidden" name="curr'.$field.'[]" value="{{ $files }}"/>
							<?php ++$cr;?>
						</li>
						@endif
					
					@endforeach
					</ul>
					';

				} else {
					$form = "<input  type='file' name='{$field}' id='{$field}' ";
					$form .= "@if(\$row['$field'] =='') class='required' @endif "; 
					$form .= "style='width:150px !important;' {$attribute} />
					 	<div >
						{!! SiteHelpers::showUploadedFile(\$row['{$field}'],'$option[path_to_upload]') !!}
						
						</div>					
					";

				}
				break;						
				
			case 'radio';
				if($required !='0') { $mandatory = 'required'; }
				$opt = explode("|",$option['lookup_query']);
				$form = '';
				for($i=0; $i<count($opt);$i++) 
				{
					$checked = '';
					$row =  explode(":",$opt[$i]); 
					$form .= "
					<label class='radio radio-inline'>
					<input type='radio' name='{$field}' value ='".ltrim(rtrim($row[0]))."' {$mandatory} {$attribute}";
					$form .= "@if(\$row['".$field."'] == '".ltrim(rtrim($row[0]))."') checked=\"checked\" @endif";
					$form .= " > ".$row[1]." </label>";
				}
				break;
				
			case 'checkbox';
				if($required !='0') { $mandatory = 'required'; }
				$opt = explode("|",$option['lookup_query']);
				$form = "<?php \$".$field." = explode(\",\",\$row['".$field."']); ?>";
				for($i=0; $i<count($opt);$i++) 
				{
					
					$checked = '';
					$row =  explode(":",$opt[$i]);					
					 $form .= "
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='{$field}[]' value ='".ltrim(rtrim($row[0]))."' {$mandatory} {$attribute} class='{$extend_class}' ";
					$form .= "
					@if(in_array('".trim($row[0])."',\$".$field."))checked @endif 
					";
					$form .= " /> ".$row[1]." </label> ";					
				}
				break;				
			
		}
		
		return $form;		
	}
	
	public static function toView( $grids )
	{
		//usort($grids, $this->_sort);
		$f = '';
		foreach($grids as $grid)
		{
			if(isset($grid['conn']) && is_array($grid['conn']))
			{
				$conn = $grid['conn'];
				//print_r($conn);exit;
			} else {
				$conn = array('valid'=>0,'db'=>'','key'=>'','display'=>'');
			}

			// IF having Connection
			if($conn['valid'] =='1') {
				$c = implode(':',$conn);
				$val = "{{ SiteHelpers::formatLookUp(\$row->".$grid['field'].",'".$grid['field']."','$c') }}";
			}			
			
			if($grid['detail'] =='1')  
			{
				$format_as = (isset($grid['format_as']) ? $grid['format_as'] : '' );
				$format_value = (isset($grid['format_value']) ?  $grid['format_value'] : '');

				preg_match('~{([^{]*)}~i',$format_value, $match);
				if(isset($match[1]))
				{
					$real_value = '{{$row->'.$match[1].'}}';
					$format_value	= str_replace($match[0],$real_value,$format_value);
				}

				if($format_as =='radio' or $format_as =='file' or $format_as == 'checkbox' or $format_as =='image' or $format_as =='database'){
					$val = "{!! SiteHelpers::formatRows(\$row->".$grid['field'].",\$fields['".$grid['field']."'],\$row ) !!}";
				} elseif($format_as =='link') {


					if($conn['valid'] =='1') {
						$val = $val;
					} else {
						$val = '{{ $row->'.$grid['field'].'}}'; 	
					}
					$val = '<a href="'.$format_value.'">'.$val.' </a>';

				
				} elseif($format_as =='date') {
					$val = "{{ date('".$format_value."',strtotime(\$row->".$grid['field'].")) }}";
				} else {
					if($conn['valid'] =='1') {
						$val = $val;
					} else {
						$val = '{{ $row->'.$grid['field'].'}}'; 	
					}  	
				} 				

					if(isset($grid['limited']) && $grid['limited'] !='')
					{
						$limited_start = 
						'
			<?php 
			$limited = isset($fields[\''.$grid['field'].'\'][\'limited\']) ? $fields[\''.$grid['field'].'\'][\'limited\'] :\'\';
			if(SiteHelpers::filterColumn($limited )) { ?>
						';
						$limited_end = '
			<?php } ?>'; 
					} else {
						$limited_start = '';
						$limited_end = ''; 
					}

				$f .= $limited_start;
				$f .= "
					<tr>
						<td width='30%' class='label-view text-right'>".$grid['label']."</td>
						<td>".$val." </td>
						
					</tr>
				";
				$f .= $limited_end;
			}
		}
		return $f;
	}
	
	public static function toMasterDetail( $info )
	{

		 if(count($info)>=1)
		 {
		 	$module = ucwords($info['module']);
		 	//$data['masterdetailmodel'] 	= '$this->modelview = new  \App\Models\''.$module.'();';
		 	
		 	$data['masterdetailinfo'] 	= "\$this->data['subgrid']	= (isset(\$this->info['config']['subgrid']) ? \$this->info['config']['subgrid'][0] : array()); ";
		 	$data['masterdetailgrid'] 	= "\$this->data['subgrid'] = \$this->detailview(\$this->modelview ,  \$this->data['subgrid'] ,\$id );";	
		 	$data['masterdetailsave'] 	= "\$this->detailviewsave( \$this->modelview , \$request->all() ,\$this->info['config']['subform'] , \$id) ;";
		 	$data['masterdetailsubform'] =  "\$this->data['subform'] = \$this->detailview(\$this->modelview ,  \$this->info['config']['subform'] ,\$id );";
		 	$tpl = array();
		 	require_once('../resources/views/sximo/module/template/native/masterdetailform.php');
	
		 	$data['masterdetailview'] 	= $tpl['masterdetailview'];
		 	$data['masterdetailform'] 	= $tpl['masterdetailform'];
		 	$data['masterdetailjs'] 	= $tpl['masterdetailjs'];
		 	$data['masterdetaildelete']	= $tpl['masterdetaildelete'];
		 	$data['masterdetailmodel'] 	= $tpl['masterdetailmodel'];
		 }
		 return $data;

	}
	
	public static function blend($str,$data) 
    {
		$src = $rep = array();
		
		foreach($data as $k=>$v){
			$src[] = "{".$k."}";
			$rep[] = $v;
		}
		
		if(is_array($str)){
			foreach($str as $st ){
				$res[] = trim(str_ireplace($src,$rep,$st));
			}
		} else {
			$res = str_ireplace($src,$rep,$str);
		}
		
		return $res;
		
    }
	
	public function createPreCombo($field , $table , $key ,  $val ,$app ,$class ,$lookey = null)
	{
		$parent = null;
		$parent_field = null;
		if($lookey != null)  
		{	
			$parent = " parent: '#".$lookey."',";
			$parent_field =  "&parent={$lookey}:";
		}	
		$pre_jCombo = "
		\$(\"#{$field}\").jCombo(\"{!! url('{$class}/comboselect?filter={$table}:{$key}:{$val}') !!}$parent_field\",
		{ ".$parent." selected_value : '{{ \$row[\"{$field}\"] }}' });
		";	
		return $pre_jCombo;
    }

    public static function formatRows($value, $attr, $row = null)
	{
		$conn = (isset($attr['conn']) ? $attr['conn'] : ['valid'=> 0,'db'=>'', 'key'=>'', 'display'=>'']);
		$field = $attr['field'];
		$formatAs = (isset($attr['format_as']) ?  $attr['format_as'] : '');
		$formatValue = (isset($attr['format_value']) ?  $attr['format_value'] : '');

		if ($conn['valid'] =='1'){
            $conn = implode(':',$conn);
			$value = self::dbLookUp($value, $field, $conn);
		}
		
        preg_match('~{([^{]*)}~i',$formatValue, $match);
        
		if(isset($match[1])){
			$realvalue  = $row->{$match[1]};
			$formatValue = str_replace($match[0],$realvalue,$formatValue);
		}

		if($formatAs =='image'){
			$vals = '';
			$values = explode(',', $value);
				foreach($values as $val){
					if($val != ''){
						if(file_exists('.'.$formatValue . $val)){
                            $vals .= '<a href="'.url( $formatValue . $val).'" target="_blank" class="previewImage">
                            <img src="'.asset( $formatValue . $val ).'" border="0" width="30" class="img-circle" style="margin-right:2px;" /></a>';
                            break;
                        }						
					}
                }  
            $value = $vals;
		}elseif($formatAs =='link'){
			$value = '<a href="'.$formatValue.'">'.$value.'</a>';
		}elseif($formatAs =='date'){
			if($formatValue ==''){
				if(config('module.date_format')){
					$value = date(config('module.date_format'), strtotime($value));
				}else{
                    $value = date('m/d/Y', strtotime($value));
                } 
			} else {
                $value = date($formatValue, strtotime($value));
            }          
		}elseif($formatAs == 'file'){
			$vals = '';
			$values = explode(',',$value);
			foreach($values as $val){

				if(file_exists('.'.$formatValue . $val)){
                    $vals .= '<a href="'.asset($formatValue. $val ).'"> '.$val.' </a><br />';
                    break;
                }	
            }
            
			$value = $vals ;

		}elseif($formatAs =='database'){
			$fields = explode("|",$formatValue);
			if(count($fields)>=2 ){

				$field_table  =  str_replace(':',',',$fields[2]);
                $fieldToShow  =  explode(":",$fields[2]);
                try {
                    $result = DB::select("SELECT ".$field_table." FROM ".$fields[0]." WHERE ".$fields[1]." IN(".$value.")");
					if(count($result) >= 1 ){
						$value = '';
						foreach($result as $row){
							$subValue = '';
							foreach($fieldToShow as $fld){
								$subValue .= $row->{$fld}.' '; 
							}	
							$value .= $subValue.', ';
						}
						$value = substr($value,0,($value-2));
					}
                }catch(\Throwable $th) {
                    $value = '';
                }		
			}
		}elseif($formatAs == 'checkbox' || $formatAs =='radio'){		
			$values = explode(',',$formatValue);
			if(count($values)>=1){
				for($i=0; $i<count($values); $i++){
					$val = explode(':',$values[$i]);
					if(trim($val[0]) == $value) 
						$value = $val[1];	
				}			
			}else{
				$value = '';	
			}
        }elseif($formatAs == 'yesno'){
			if($value == 1)
				$value = 'Yes';
			else
				$value = 'No';
		}elseif($formatAs == 'status'){
			if($value == 1 || is_null($value))
				$value = '<label class="bg-success text-white">Active</label>';
			else
				$value = '<label class="bg-danger text-white">Inactive</label>';
		}
		return $value;
    }
    
    public static function dbLookUp($val, $field, $arr)
	{
		$arr = explode(':', $arr);
		
		if(isset($arr['0']) && $arr['0']==1){
            $columns = str_replace("|",",",$arr['3']);
            $table = trim($arr['1']);
            $condition = trim($arr['2']);
            $result = \DB::select("SELECT ".$columns." FROM ".$table." WHERE ".$condition." = '".$val."'");
            
			if(count($result) >= 1 )
			{
                $row = $result[0];
                $fields = explode('|', $arr['3']);            
				$value = isset($fields[1]) && $fields[1] !=''  ? $row->{$fields[1]} : '';
				return $value;
			} else {
				return "";
			}
		} else {
			return $val;
		}		
	}

	public static function limiTo($limit)
	{
		/* if($limit !=''){
			$limited = explode(',',$limit);	
			if(in_array( \Session::get('uid'),$limited) ){
				return  true;
			}else{
				return false;	
			}
		}else{
			return true;
		} */

		return true;
	}
}
