<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
    	'internal_code',
		'identification_number',
		'name',
		'lastname',
		'status',
		'email',
		'city',
		'address',
		'phone',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Obtiene el nombre completo del empleado
     * 
     * @return string
     */
    public function getFullnameAttribute()
    {
        return $this->attributes['lastname'] .' '. $this->attributes['name'];
    }

    /**
     * Gestiona el proceso de importación de datos a la tabla de empleados.
     * 
     * @param  array $data
     * @return array
     */
    public function importData($data)
    {
        // array con la info de los datos procesados
        $processedData = [];
        // los mensajes de validación
        $this->validationMessages = [];

        // TO-DO:
        // - Evaluar posibilidades de refactor para performance eliminando los indices
        // del array que ya hayan sido procesados y usando "database transactions"
        // - Mostrar el tiempo total de procesamiento de la información.

    	// proceso los registros nuevos a crear
        $processedData['created_rows'] = $this->syncCreatedData($data);

    	// proceso los registros a actualizar
    	$processedData['updated_rows'] = $this->syncUpdatedData($data);

    	// proceso los registros a eliminar
    	$processedData['deleted_rows'] = $this->syncDeletedData($data);

    	// devuelvo la info de registros procesados
    	return $processedData;
    }

    /**
     * Sincroniza la nueva info de empleados en la base de datos dada en al array $data.
     * 
     * @param  array $data
     * @return array
     */
    private function syncCreatedData($data)
    {
        // conteo de registros creados
        $rowsProcessed = 0;
        // conteo de registros ignorados
        $rowsIgnored = 0;
        // conteo de registros con errores de validación
        $rowsInvalid = 0;

        // recorro cada elemento del array de datos a importar
        foreach ($data as $key => $row) {

            // valído el formato de los datos a importar
            // y que no sea el caso de que se quiera eliminar un registro
            if (($validator = $this->validateDataToImport($row, $row['id'])) !== true && empty($row['deleted_at'])){
                // obtengo los mensajes de error
                $this->validationMessages += $validator->errors()->all();
                // incremento el numero de registros inválidos
                $rowsInvalid++;
                // sigo con la siguiente fila
                continue;
            }

            // trato de encontrar el registro, si no creo una nueva instancia del modelo
            $model = $this->where('id', $row['id'])->withTrashed()->first();
            $model = $model ? $model : $this->newInstance();

            // si no existe el modelo
            if (! $model->exists){

                // le asigno los respectivos valores al modelo
                $model->fill($row);
                // guardo el modelo
                $model->save();
                // incremento la variable de conteo de registros procesados
                $rowsProcessed++;
                // sigo con la siguiente fila
                continue;

            }
            
            // incremento la variable de conteo de registros ignorados
            $rowsIgnored++;
        }

        return ['processed' => $rowsProcessed, 'invalid' => $rowsInvalid, 'ignored' => $rowsIgnored];
    }

    /**
     * Sincroniza los cambios realizados a la info de empleados dados en el array $data a importar.
     * 
     * @param  array $data
     * @return int
     */
    private function syncUpdatedData($data)
    {
        // conteo de registros creados
        $rowsProcessed = 0;
        // conteo de registros ignorados
        $rowsIgnored = 0;
        // conteo de registros con errores de validación
        $rowsInvalid = 0;
        
        // recorro cada elemento del array de datos a importar
        foreach ($data as $key => $row) {

            // variable de control para saber si hay algo para actualizar a un modelo
            $needUpdate = false;

            // valído el formato de los datos a importar
            if (($validator = $this->validateDataToImport($row, $row['id'])) !== true){
                // obtengo los mensajes de error
                $this->validationMessages += $validator->errors()->all();
                // incremento el numero de registros inválidos
                $rowsInvalid++;
                // sigo con la siguiente fila
                continue;
            }

            // trato de encontrar el registro, si no creo una nueva instancia del modelo
            $model = $this->where('id', $row['id'])->withTrashed()->first();

            // si es encontrado
            if ($model){

                // recorro los atributos que deseo actualizar
                foreach ($this->fillable as $attributeKey => $attribute) {

                    // los atributos del modelo son diferentes a los de la fila a importar?
                    // y el atributo "deleted_at" está vacío?
                    if ($model->{$attribute} != $row[$attribute] && empty($row['deleted_at'])){
                        // asigno el nuevo valor al atributo del modelo
                        $model->{$attribute} = $row[$attribute];
                        // si, se necesitan guardar estos cambios
                        $needUpdate = true;
                    }

                }

                // se detectaron cambios que hacer
                if ($needUpdate){
                    // guardo el modelo
                    $model->save();
                    // incremento la variable de conteo de registros procesados
                    $rowsProcessed++;
                    // sigo con la siguiente fila
                    continue;
                }

                // incremento la variable de conteo de registros ignorados
                $rowsIgnored++;
            }
        }

        return ['processed' => $rowsProcessed, 'invalid' => $rowsInvalid, 'ignored' => $rowsIgnored];
    }

    /**
     * Sincroniza los elementos que han sido borrados de la base de datos con base en
     * el array $data a importar.
     * 
     * @param  array $data
     * @return int
     */
    private function syncDeletedData($data)
    {
        // conteo de registros creados
        $rowsProcessed = 0;
        // conteo de registros ignorados
        $rowsIgnored = 0;
        // conteo de registros con errores de validación
        $rowsInvalid = 0;

        // recorro cada elemento del array de datos a importar
        foreach ($data as $key => $row) {

            // si el atributo "deleted_at" NO ES NULO, la fila debe ser eliminada
            if (! empty($row['deleted_at'])){

                // valído el formato de los datos a importar
                if (($validator = $this->validateDataToImport($row, true)) !== true){
                    // obtengo los mensajes de error
                    $this->validationMessages += $validator->errors()->all();
                    // incremento el numero de registros inválidos
                    $rowsInvalid++;
                    // sigo con la siguiente fila
                    continue;
                }

                // obtengo el modelo
                $model = $this->find($row['id']);

                // si fue encontrado
                if ($model && ! empty($row['deleted_at'])){
                    // le asigno el respectivo valor
                    $model->deleted_at = $row['deleted_at'];
                    // guardo los cambios
                    $model->save();
                    // incremento la variable de conteo de registros procesados
                    $rowsProcessed++;
                    // sigo con la siguiente fila
                    continue;
                }

                // un registro se quería eliminar, pero no fue encontrado en la base de datos
                // incremento la variable de conteo de registros ignorados
                $rowsIgnored++;
            }
        }

        // borro los registros que no estén en el array de datos a importar
        $rowsProcessed += $this->whereNotIn('id', array_pluck($data, 'id'))->whereNull('deleted_at')->delete();

        // ahora todo lo que no esté en el array de datos a importar, debe ser eliminado,
        // junto con los registros que no tengan NULO el valor del atributo "deleted_at"
        return [
            'processed' => $rowsProcessed,
            'invalid'   => $rowsInvalid,
            'ignored'   => $rowsIgnored
        ];
    }

    /**
     * Valida la información de los datos a importar.
     * 
     * @param  array $data
     * @return mixed
     */
    private function validateDataToImport($data, $id = 0)
    {
        // las reglas de validación
        $rules = $this->getValidationRules();

        // si se quiere validar para actualización, modifico las reglas de validación para que
        // ignore los respectivos id de los registros a actualizar en los campos que son únicos
        if ($id != 0){
            $rules['internal_code']         = "required|numeric|unique:employees,internal_code,{$id},id";
            $rules['identification_number'] = "required|numeric|unique:employees,identification_number,{$id},id";
            $rules['email']                 = "email|unique:employees,email,{$id},id";
        }

        // valido la información
        $validator = \Validator::make(
            $data,
            $rules,
            [
                'id.required'   => 'El id del empleado es un campo obligatorio.',
                'id.numeric'    => 'El id del empleado debe ser numérico.'
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }

        return true;
    }

    /**
     * Devuelve las reglas de validación.
     * 
     * @return array
     */
    private function getValidationRules()
    {
        return [
            'id'                    => 'required|numeric',
            'internal_code'         => 'required|numeric|unique:employees',
            'identification_number' => 'required|numeric|unique:employees',
            'name'                  => 'required|min:3|max:50|alpha_spaces',
            'lastname'              => 'required|min:3|max:50|alpha_spaces',
            'status'                => 'alpha_spaces',
            'email'                 => 'email|unique:employees',
            'city'                  => 'alpha_spaces',
            'address'               => 'text',
            'phone'                 => 'numbers_spaces_dashes',
            'created_at'            => 'date_format:Y-m-d H:i:s',
            'updated_at'            => 'date_format:Y-m-d H:i:s',
            'deleted_at'            => 'date_format:Y-m-d H:i:s'
        ];
    }
}
