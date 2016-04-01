<?php

namespace llstarscreamll\EmployeesModule\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use llstarscreamll\CoreModule\app\Models\CostCenter;
use llstarscreamll\CoreModule\app\Models\SubCostCenter;
use llstarscreamll\EmployeesModule\app\Models\Position;
use llstarscreamll\EmployeesModule\app\Models\Employee;
use llstarscreamll\EmployeesModule\app\Http\Requests\EmployeeFormRequest;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // el usuario debe estar autenticado para acceder al controlador
        $this->middleware('auth');
        // el usuario debe tener permisos para acceder al controlador
        $this->middleware('checkPermissions', ['except' => ['store', 'update']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $employees = Employee::with('position', 'subCostCenter', 'subCostCenter.costCenter');

        if($request->has('show_only')){
            switch ($request->get('show_only')) {
                case 'trashed':
                    $employees = $employees->onlyTrashed();
                    break;
                case 'enabled':
                    $employees = $employees->where('status', 'enabled');
                    break;
                case 'disabled':
                    $employees = $employees->where('status', 'disabled');
                    break;
                default:
                    break;
            }
        }

        $employees = $employees->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->get('find').'%')
                    ->orWhere('lastname', 'like', '%'.$request->get('find').'%')
                    ->orWhere('identification_number', 'like', '%'.$request->get('find').'%')
                    ->orWhere('internal_code', 'like', '%'.$request->get('find').'%');
            })->orderBy('updated_at', 'desc')->paginate(15);

        return view('EmployeesModule::employees.index', compact('employees', 'input'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cost_centers = CostCenter::getOrderListWithSubCostCenters();
        $positions = Position::orderBy('name')->lists('name', 'id')->toArray();

        return view('EmployeesModule::employees.create', compact('cost_centers', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeFormRequest $request)
    {
        $employee = new Employee();
        $employee->sub_cost_center_id = $request->get('sub_cost_center_id');
        $employee->position_id = $request->get('position_id');
        $employee->name = $request->get('name');
        $employee->lastname = $request->get('lastname');
        $employee->identification_number = $request->get('identification_number');
        $employee->internal_code = $request->get('internal_code');
        $employee->city = $request->get('city');
        $employee->address = $request->get('address');
        $employee->phone = $request->get('phone');
        $employee->email = empty(trim($request->get('email'))) ? null : $request->get('email');

        $employee->save()
            ? $request->session()->flash('success', 'Empleado creado correctamente.')
            : $request->session()->flash('error', 'Ocurrió un error creando el empleado.');

        return redirect()->route('employee.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);

        return view('EmployeesModule::employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        $cost_centers = CostCenter::getOrderListWithSubCostCenters();
        $positions = Position::orderBy('name')->lists('name', 'id')->toArray();

        return view('EmployeesModule::employees.edit', compact('employee', 'cost_centers', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeFormRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->fill($request->all());

        $employee->save()
            ? $request->session()->flash('success', 'Empleado actualizado correctamente.')
            : $request->session()->flash('error', 'Ocurrió un error actualizado al empleado.');

        return redirect()->route('employee.show', $employee->id);
    }

    /**
     * Cambio el estado de los empleados de activado a desactivado.
     */
    public function status($status, Request $request)
    {
        switch ($status) {
            case 'disabled':
                $action = [
                    'singular' => 'desactivado',
                    'plural' => 'desactivados',
                ];
                break;
            case 'enabled':
            default:
                $action = [
                    'singular' => 'activado',
                    'plural' => 'activados',
                ];
                break;
        }

        if ($request->has('id')) {
            $id = is_array($request->get('id'))
                ? $request->get('id')
                : [$request->get('id')];

            Employee::whereIn('id', $id)->update(['status' => $status])
                ? $request->session()->flash('success', [is_array($id) && count($id) > 1
                    ? 'Los empleados han sido '.$action['plural'].' correctamente.'
                    : 'El empleado ha sido '.$action['singular'].' correctamente.', ])
                : $request->session()->flash('error', [is_array($id)
                    ? 'Los empleados no pudieron ser '.$action['plural'].'.'
                    : 'El empleado no pudo ser '.$action['singular'].'.', ]);
        } else {
            $request->session()->flash('warning', 'Ningún empleado que activar.');
        }

        return redirect()->route('employee.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $id = $request->has('id') ? $request->get('id') : $id;

        Employee::destroy($id)
            ? $request->session()->flash('success', [is_array($id) && count($id) > 1
                ? 'Los empleados han sido movidos a la papelera correctamente.'
                : 'El empleado ha sido movido a la papelera correctamente.'])
            : $request->session()->flash('error', [is_array($id)
                ? 'Ocurrió un error moviendo los empleados a la papelera.'
                : 'Ocurrió un problema moviendo el empleado a la papelera.']);

        return redirect()->route('employee.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id, Request $request)
    {
        $id = $request->has('id') ? $request->get('id') : [$id];

        Employee::whereIn('id', $id)->restore()
            ? $request->session()->flash('success', [is_array($id) && count($id) > 1
                ? 'Los empleados han sido restaurados correctamente.'
                : 'El empleado ha sido restaurado correctamente.'])
            : $request->session()->flash('error', [is_array($id)
                ? 'Ocurrió un error restaurando los empleados.'
                : 'Ocurrió un problema restaurando el empleado.']);

        return redirect()->route('employee.index');
    }

    /**
     * Genera un archivo que contiene la información de todos los empleados registrados.
     */
    public function exportToExcel()
    {
        \Excel::create('RegistrosDeEmpleadosELCaballo', function ($excel) {

            $excel->sheet('Empleados', function ($sheet) {

                $data = Employee::getDataToExportExcel();
                $sheet->fromArray($data);

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 10,
                        'bold' => false,
                    ),
                ));

            });

        })->export('xlsx');
    }

    /**
     * La vista en la que el usuario carga el archivo a ser importado.
     * 
     * @return \Illuminate\Http\Response
     */
    public function prepare_import()
    {
        return view('EmployeesModule::employees.import-form');
    }

    /**
     * Importa información de empleados desde un documento de excel dado por el usuario.
     * 
     * @param EmployeeFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function post_import(EmployeeFormRequest $request)
    {
        $file = $request->file('file')->move(storage_path('imports/'), $name = date('Y-m-d_H-i-s').'.xlsx');
        $data = array();
        $unique_data = array();
        $unique_data['identification_number'] = array();
        $unique_data['internal_code'] = array();
        $unique_data['email'] = array();

        $start = microtime(true);

        try {
            
            if (count($document = \Excel::selectSheets('Empleados')
                ->load(storage_path('imports/').$name)
                ->ignoreEmpty()
                ->get()) == 0) {
                return redirect()->back()->withError('No se encontró la hoja Empleados dentro del libro, por favor verifica los requisitos del documento.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withError('Un error ha ocurrido, por favor comprueba los requisitos del documento, información detallada del error: '.$e->getMessage());
        }

        foreach ($document as $key => $row) {

            $validator = \Validator::make([
                'centro de costo' => trim($row->centro_costo),
                'subcentro de costo' => trim($row->subcentro_costo),
                'cargo' => trim($row->cargo),
                'nombre' => trim($row->nombres),
                'apellido' => trim($row->apellidos),
                'identificación' => intval(trim($row->cedula)),
                'código interno' => intval(trim($row->codigo)),
                'ciudad' => trim($row->ciudad),
                'dirección' => trim($row->direccion),
                'teléfono' => trim($row->telefono),
                'email' => trim($row->email) != '' ? strtolower(trim($row->email)) : null,
            ], [
                'centro de costo' => 'required|alpha_numbers_spaces',
                'subcentro de costo' => 'alpha_numbers_spaces',
                'cargo' => 'required|alpha_spaces',
                'nombre' => 'required|min:3|max:50|alpha_spaces',
                'apellido' => 'required|min:3|max:50|alpha_spaces',
                'identificación' => 'required|numeric|unique:employees,identification_number,'.intval(trim($row->codigo)).',internal_code',
                'código interno' => 'required|numeric|unique:employees,internal_code,'.intval(trim($row->cedula)).',identification_number',
                'ciudad' => 'alpha_spaces',
                'dirección' => 'text',
                'teléfono' => 'numbers_spaces_dashes',
                'email' => 'email|unique:employees,email,'.intval(trim($row->cedula)).',identification_number',
            ], [
                'nombre.alpha_spaces' => 'El nombre sólo puede contener letras y/o espacios.',
                'nombre.required' => 'El nombre es un campo obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos :min caracteres.',
                'nombre.max' => 'El nombre debe tener máximo :max caracteres.',

                'apellido.required' => 'El apellido es un campo obligatorio.',
                'apellido.alpha_spaces' => 'El apellido sólo puede contener letras y/o espacios.',
                'apellido.min' => 'El apellido debe tener al menos :min caracteres.',
                'apellido.max' => 'El apellido debe tener máximo :max caracteres.',

                'identificación.required' => 'El número de identificación es un campo obligatorio.',
                'identificación.unique' => 'El número de identificación '.intval(trim($row->cedula)).' de '.trim($row->nombres).' '.trim($row->apellidos).' ya está asociado a otro empleado en el sistema.',
                'identificación.numeric' => 'El número de identificación '.intval(trim($row->cedula)).' de '.trim($row->nombres).' '.trim($row->apellidos).' tiene un formato incorrecto, sólo se permiten números.',

                'código interno.required' => 'El código es un campo obligatorio.',
                'código interno.unique' => 'El código '.intval(trim($row->codigo)).' de '.trim($row->nombres).' '.trim($row->apellidos).' ya está asociado a otro empleado en el sistema.',
                'código interno.numeric' => 'El código '.intval(trim($row->codigo)).' de '.trim($row->nombres).' '.trim($row->apellidos).' tiene un formato incorrecto, sólo se permiten números.',

                'email.email' => 'La dirección de correo electrónico '.strtolower(trim($row->email)).' no es válida.',
                'email.unique' => 'La dirección de correo '.strtolower(trim($row->email)).' pertenece a otro empleado.',

                'centro de costo.required' => 'Elige el centro de costo al que pertenece el empleado.',
                'centro de costo.alpha_numbers_spaces' => 'El centro de costo '.trim($row->centro_costo).' tiene formato inválido, sólo se permiten letras, números y/o espacios.',

                'subcentro de costo.alpha_numbers_spaces' => 'El subcentro de costo '.trim($row->subcentro_costo).' tiene formato inválido, sólo se permiten letras, números y/o espacios.',

                'cargo.required' => 'Debes elegir un cargo para '.trim($row->nombres).' '.trim($row->apellidos).'.',
                'cargo.exists' => 'El cargo de '.trim($row->nombres).' '.trim($row->apellidos).' no existe.',
                'cargo.alpha_spaces' => 'El cargo '.trim($row->cargo).' tiene formato inválido, sólo se permiten letras y/o espacios.',

                'ciudad.alpha_spaces' => 'En el formato de la ciudad sólo se permiten letras y/o espacios, '.trim($row->ciudad).' es una ciudad inválida.',

                'dirección.text' => 'En el formato de la dirección sólo se permiten letras, números, espacios, puntos, comas, guiones y/o arroba (@), '.trim($row->direccion).' es una dirección inválida.',

                'teléfono.numbers_spaces_dashes' => 'En el formato del teléfono sólo se permiten números, espacios y/o guines, '.trim($row->telefono).' es un número telefónico inválido.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withError($validator->errors()->all());
            }

            if (!in_array(intval(trim($row->cedula)), $unique_data['identification_number']) &&
                !in_array(intval(trim($row->codigo)), $unique_data['internal_code']) &&
                !in_array(strtolower(trim($row->email)), $unique_data['email'])) {
                $file_data['employees'][intval($row->codigo)] = [
                    'identification_number' => intval(trim($row->cedula)),
                    'internal_code' => intval(trim($row->codigo)),
                    'name' => trim($row->nombres),
                    'lastname' => trim($row->apellidos),
                    'city' => trim($row->ciudad),
                    'address' => trim($row->direccion),
                    'phone' => trim($row->telefono),
                    'email' => trim($row->email) != '' ? strtolower(trim($row->email)) : null,
                    'position_id' => trim($row->cargo),
                    'sub_cost_center_id' => empty(trim($row->sub_centro_costo)) ? trim($row->centro_costo) : trim($row->sub_centro_costo),
                ];

                $file_data['positions'][trim($row->cargo)] = trim($row->cargo);
                $file_data['cost_centers'][trim($row->centro_costo)] = trim($row->centro_costo);

                // la llave que es el nombre del centro de costo em va a decir a que centro pertenece el subcentro
                $file_data['sub_cost_centers'][trim($row->centro_costo)][] = empty($row->sub_centro_costo) ? trim($row->centro_costo) : trim($row->sub_centro_costo);

                $file_data['employees_codes'][] = intval($row->codigo);

                $unique_data['identification_number'][] = intval(trim($row->cedula));
                $unique_data['internal_code'][] = intval(trim($row->codigo));
                trim($row->email) != '' ? $unique_data['email'][] = strtolower(trim($row->email)) : null;
            } elseif (in_array(intval(trim($row->cedula)), $unique_data['identification_number'])) {
                $file_data['duplicate'][] = [
                    'reason' => 'identification_number',
                    'duplicate_value' => intval(trim($row->cedula)),
                    'employee_info' => $row,
                    ];
            } elseif (in_array(intval(trim($row->codigo)), $unique_data['internal_code'])) {
                $file_data['duplicate'][] = [
                    'reason' => 'internal_code',
                    'duplicate_value' => intval(trim($row->codigo)),
                    'employee_info' => $row,
                    ];
            } elseif (in_array(strtolower(trim($row->email)), $unique_data['email'])) {
                $file_data['duplicate'][] = [
                    'reason' => 'email',
                    'duplicate_value' => intval(trim($row->email)),
                    'employee_info' => $row,
                    ];
            }
        }

        try {

            // realizo el proceso de importación
            $cost_centers_data = CostCenter::importData($file_data['cost_centers']);
            $sub_cost_centers_data = SubCostCenter::importData($file_data['sub_cost_centers'], $file_data['cost_centers']);
            $positions_data = Position::importData($file_data['positions']);
            $employees_data = Employee::importData($file_data);
        } catch (\Exception $e) {
            return redirect()->back()->withError('Un error ha ocurrido, por favor verifica que no existan duplicados entre los valores únicos de tu archivo y la base de datos, como por ejemplo que un empleado de tu archivo tenga el email, código interno o número de cédula de otro en la base de datos, pues son campos únicos y no puede haber dos empleados con los mismos datos, mas detalles del error... '.$e->getMessage());
        }

        $file_data['employee_statistics'] = $employees_data;
        $file_data['position_statistics'] = $positions_data;
        $file_data['cost_center_statistics'] = $cost_centers_data;
        $file_data['sub_cost_center_statistics'] = $sub_cost_centers_data;
        $file_data['total_time_elapsed'] = microtime(true) - $start;

        return redirect()->back()->with('file_data', $file_data);
    }
}
