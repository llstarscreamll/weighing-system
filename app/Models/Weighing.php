<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weighing extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'weighings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'employee_id',
		'machine_id',
		'product_id',
		'weight'
    ];

    /**
     * La relación entre pesajes y empleados
     * @return [type] [description]
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }

    /**
     * Fuente de éste código:
     * http://edmondscommerce.github.io/php/barcode/ean13-barcode-check-digit-with-php.html
     * @param  string $digits
     * @return string
     */
    public function getCheckDigit($digits)
    {
        //first change digits to a string so that we can access individual numbers
        $digits =(string)$digits;
        // 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
        $even_sum = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
        // 2. Multiply this result by 3.
        $even_sum_three = $even_sum * 3;
        // 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
        $odd_sum = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
        // 4. Sum the results of steps 2 and 3.
        $total_sum = $even_sum_three + $odd_sum;
        // 5. The check character is the smallest number which, when added to the result in step 4,  produces a multiple of 10.
        $next_ten = (ceil($total_sum/10))*10;
        $check_digit = $next_ten - $total_sum;

        return $check_digit;
    }

    /**
     * Genera el código de barras.
     * @return string
     */
    public function getBarcode()
    {
        // obtengo id del producto
        switch ($this->product_id) {
            case 'Producto 1':
                $product_id = 1262;
                break;
            case 'Producto 2':
                $product_id = 2654;
                break;
            case 'Producto Test':
                $product_id = 21400;
                break;
            default:
                $product_id = 0;
                break;
        }

        // lleno con 0 el dato del id del producto si es que le hace falta
        $product_id = str_pad($product_id, 5, "0", STR_PAD_LEFT);
        // quito el punto decimal del peso
        $weight = str_pad(str_replace(".", "", $this->weight), 4, "0", STR_PAD_LEFT);

        return '770'.$product_id.$weight.$this->getCheckDigit('770'.$product_id.$weight);
    }
}
