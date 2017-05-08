<?php

include("pData.class.php");
include("pChart.class.php");

class chart{

	var $w;
	var $h;
	var $chart;

	public function __construct($w, $h){
		$this->w = $w;
		$this->h = $h;
		$this->chart = new pChart($w,$h);
		$this->chart->drawFilledRoundedRectangle(7,7,($w-8),($h-8),5,240,240,240);
		$this->chart->drawRoundedRectangle(5,5,($w-5),($h-5),5,230,230,230);
		$this->chart->setFontProperties("fonts/tahoma.ttf",8);  
		$this->chart->setShadowProperties(2,2,200,200,200);
	}
		
	public function drawPIE($data, $labels, $title=''){
		if(count($data) == count($labels)){
			$DataSet = new pData;
			$DataSet->AddPoint($data,"Serie1");
			$DataSet->AddPoint($labels,"Serie2");
			$DataSet->AddAllSeries();
			$DataSet->SetAbsciseLabelSerie("Serie2");
			$this->chart->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),$this->w*.4,$this->h/2,((sqrt((pow($this->w,2)+pow($this->h,2))))/6),PIE_PERCENTAGE_LABEL,10);
			$this->chart->drawPieLegend($this->w-150,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
		}
		else{
			$this->chart->drawTextBox(($w/3),($w/3),($w/2),($h/2),"Error: Data/Label Mismatch",0,255,255,255,ALIGN_RIGHT,TRUE,0,0,0,30);
		}
	}

	private function set_ticks($val,$DataSet){
		(strlen($val[0])>5?$this->chart->setXLabelFontSize(6):NULL);
		$DataSet->AddPoint($val, "Ticks");
		$DataSet->SetAbsciseLabelSerie("Ticks");
	}
	
	public function drawBAR($data, $labels, $title=''){

		if(!empty($data)){
			$DataSet = new pData;
			$i=1;
			$DataSet->SetAbsciseLabelSerie();
			foreach($data as $key => $var){
				$DataSet->AddPoint($var, "Serie".$i);
				$DataSet->SetSerieName($key, "Serie".$i);
				$i++;
			}
			$DataSet->AddAllSeries();
			
			if(!empty($labels)){
				(isset($labels['y_axis_label'])?$DataSet->SetYAxisName($labels['y_axis_label']):NULL);
				(isset($labels['x_axis_label'])?$DataSet->SetXAxisName($labels['x_axis_label']):NULL);
				(isset($labels['y_axis_unit'])?$DataSet->SetYAxisUnit($labels['y_axis_unit']):NULL);
				(isset($labels['x_axis_unit'])?$DataSet->SetXAxisUnit($labels['x_axis_unit']):NULL);
				(isset($labels['y_axis_format'])?$DataSet->SetYAxisFormat($labels['y_axis_format']):NULL);
				(isset($labels['x_axis_format'])?$DataSet->SetXAxisFormat($labels['x_axis_format']):NULL);
				(isset($labels['x_axis_ticks'])?$this->set_ticks($labels['x_axis_ticks'],$DataSet):NULL);
			}
			$this->chart->setShadowProperties(0,0,255,255,255);
			$this->chart->setGraphArea(($this->w*.1*2),30,$this->w-20,$this->h-30);
			$this->chart->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);
			$this->chart->drawGrid(10,TRUE,230,230,230,50);
			$this->chart->drawLegend($this->w*.6,15,$DataSet->GetDataDescription(),250,250,250);
			$this->chart->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);
//			$this-chart->setFontProperties("Fonts/tahoma.ttf",6);
//			$this->chart->drawTreshold(0,143,55,72,TRUE,TRUE);
			if(!empty($title)){
				$this->chart->setFontProperties("fonts/tahoma.ttf",10);
				$this->chart->drawTitle($this->w*.9,22,$title,50,50,50,$this-w*.95,22);
			}
		}
		else{
			$this->chart->drawTextBox(($w/3),($w/3),($w/2),($h/2),"Error: Data/Label Mismatch",0,255,255,255,ALIGN_RIGHT,TRUE,0,0,0,30);
		}
	}

	public function __destruct(){
		$this->chart->Stroke();
	}
	
	
}



?>