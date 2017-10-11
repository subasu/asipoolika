<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceController extends Controller
{
    //First part of a price: Ex : 230.576.000.000 it shows 230
    public function holePrice($price,$count)
    {
//    dd($count);
        switch($count)
        {
            //Thousand
            case 4:
                $a=substr($price,0,1)." هزار";
                $count=4;
                $b=$this->secondPart($price,$count);
                break;
            case 5:
                $a=substr($price,0,2)." هزار";
                $count=5;
                $b=$this->secondPart($price,$count);
                break;
            case 6:
                $a=substr($price,0,3)." هزار";
                $count=6;
                $b=$this->secondPart($price,$count);
                break;
            //Million
            case 7:
                $a=substr($price,0,1)." میلیون";
                $count=7;
                $b=$this->secondPart($price,$count);
                break;
            case 8:
                $a=substr($price,0,2)." میلیون";
                $count=8;
                $b=$this->secondPart($price,$count);
                break;
            case 9:
                $a=substr($price,0,3)." میلیون";
                $count=9;
                $b=$this->secondPart($price,$count);
                break;
            //Billion
            case 10:
                $a=substr($price,0,1)." میلیارد";
                $count=10;
                $b=$this->secondPart($price,$count);
                break;
            case 11:
                $a=substr($price,0,2)." میلیارد";
                $count=11;
                $b=$this->secondPart($price,$count);
                break;
            case 12:
                $a=substr($price,0,3)." میلیارد";
                $count=12;
                $b=$this->secondPart($price,$count);
                break;
            //Trillion
            case 13:
                $a=substr($price,0,1)." تریلیارد";
                $count=13;
                $b=$this->secondPart($price,$count);
                break;
            case 14:
                $a=substr($price,0,2)." تریلیارد";
                $count=14;
                $b=$this->secondPart($price,$count);
                break;
            case 15:
                $a=substr($price,0,3)." تریلیارد";
                $count=15;
                $b=$this->secondPart($price,$count);
                break;
        }
        $all=$a.$b;
        return $all;
    }
//This is a function To check if the 3 numbers after Billion are 345 or 042 or 005 . You can realize the meaning
// after a small trace and imagine this example about this number : 300.452.000.000
//it's about calculate 452 and these kinds of numbers
//Second part of a price: Ex : 230.576.000.000 it shows 576
    public function secondPart($sellPrice,$count)
    {
        switch($count)
        {
            case 4:
                $p=substr($sellPrice,1,3);
                $c='';
                break;
            case 5:
                $p=substr($sellPrice,2,3);
                $c='';
                break;
            case 6:
                $p=substr($sellPrice,3,3);
                $c='';
                break;
            case 7:
                $p=substr($sellPrice,1,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
            case 8:
                $p=substr($sellPrice,2,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
            case 9:
                $p=substr($sellPrice,3,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
            case 10:
                $p=substr($sellPrice,1,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
            case 11:
                $p=substr($sellPrice,2,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
            case 12:
                $p=substr($sellPrice,3,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
            case 13:
                $p=substr($sellPrice,1,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
            case 14:
                $p=substr($sellPrice,2,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
            case 15:
                $p=substr($sellPrice,3,3);
                $c=$this->thirdPart($sellPrice,$count);
                break;
        }

        $p1=substr($p,0,1);
        $p2=substr($p,1,1);
        $p3=substr($p,2,1);

        if($p1!=0)
        {
            $firstSell=$p1;
            $secondSell=$p2;
            $thirdSell =$p3;
        }
        elseif($p1==0 and $p2!=0)
        {
            $firstSell="";
            $secondSell=$p2;
            $thirdSell=$p3;
        }
        elseif($p1==0 and $p2==0 and $p3!=0)
        {
            $firstSell="";
            $secondSell="";
            $thirdSell=$p3;
        }
        elseif($p1==0 and $p2==0 and $p3==0)
        {
            $firstSell="";
            $secondSell="";
            $thirdSell="";
        }
        $num=$firstSell.$secondSell.$thirdSell;

        if(empty($num))
        {
            $suffix=" تومان";
            $andCharacter="";
        }
        else
        {
            if($count==4 or $count==5 or $count==6)
            {
                $suffix=" تومان";
                $andCharacter=" و ";
            }
            elseif($count==7 or $count==8 or $count==9)
            {
                $suffix=" هزار ";
                $andCharacter=" و ";
            }
            elseif($count==10 or $count==11 or $count==12)
            {
                $suffix=" میلیون";
                $andCharacter=" و ";
            }

            elseif($count==13 or $count==14 or $count==15)
            {
                $suffix=" میلیارد ";
                $andCharacter=" و";
            }
        }
        $num=$andCharacter.$firstSell.$secondSell.$thirdSell.$suffix.$c;
        return $num;
    }
//the third part of the price (it's for large prices)
    public function thirdPart($sellPrice,$count)
    {
        switch($count) {
            case 4:
//                $p=substr($sellPrice,1,3);
                $p='';
                break;
            case 5:
//                $p=substr($sellPrice,2,3);
                $p='';
                break;
            case 6:
//                $p=substr($sellPrice,3,3);
                $p='';
                break;
            case 7:
                $p=substr($sellPrice,4,3);
                break;
            case 8:
                $p=substr($sellPrice,5,3);
                break;
            case 9:
                $p=substr($sellPrice,6,3);
                break;
            case 10:
                $p=substr($sellPrice,4,3);
                break;
            case 11:
                $p=substr($sellPrice,5,3);
                break;
            case 12:
                $p=substr($sellPrice,6,3);
                break;
            case 13:
                $p=substr($sellPrice,4,3);
                break;
            case 14:
                $p=substr($sellPrice,5,3);
                break;
            case 15:
                $p=substr($sellPrice,6,3);
                break;
        }
        $p1=substr($p,0,1);
        $p2=substr($p,1,1);
        $p3=substr($p,2,1);

        if($p1!=0)
        {
            $firstSell=$p1;
            $secondSell=$p2;
            $thirdSell =$p3;
        }
        elseif($p1==0 and $p2!=0)
        {
            $firstSell="";
            $secondSell=$p2;
            $thirdSell=$p3;
        }
        elseif($p1==0 and $p2==0 and $p3!=0)
        {
            $firstSell="";
            $secondSell="";
            $thirdSell=$p3;
        }
        elseif($p1==0 and $p2==0 and $p3==0)
        {
            $firstSell="";
            $secondSell="";
            $thirdSell="";
        }
        $num=$firstSell.$secondSell.$thirdSell;
        if(empty($num))
        {
            $suffix="";
            $andCharacter="";
        }
        else
        {
            if($count==7 or $count==8 or $count==9)
            {
                $suffix=" تومان";
                $andCharacter=" و ";
            }
            elseif($count==10 or $count==11 or $count==12)
            {
                $suffix=" هزار تومان";
                $andCharacter=" و ";
            }
            elseif($count==13 or $count==14 or $count==15)
            {
                $suffix=" میلیون تومان";
                $andCharacter=" و ";
            }
            elseif($count==7 or $count==8 or $count==9)
            {
                $suffix=" هزار تومان";
                $andCharacter=" و ";
            }
            elseif($count==10 or $count==11 or $count==12)
            {
                $suffix=" میلیون تومان";
                $andCharacter=" و ";
            }

            elseif($count==13 or $count==14 or $count==15)
            {
                $suffix=" میلیارد تومان";
                $andCharacter=" و";
            }
        }
        $num=$andCharacter.$firstSell.$secondSell.$thirdSell.$suffix;

        return $num;
    }

}
