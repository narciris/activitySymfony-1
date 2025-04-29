<?php

namespace App\Controller;

use App\Dtos\EmployeeRequestDto;
use App\Entity\Employee;
use App\Service\EmployeeService;
use PharIo\Manifest\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/employees')]
class EmployeeController extends AbstractController
{
    private $employeeService;

    public function __construct(EmployeeService $employeeService){
        $this->employeeService = $employeeService;
    }

    #[Route('/create', name:'employee_create', methods: ['GET'])]
    public function create()
    {
        return $this->render('employees/create.html.twig');
    }
    #[Route('/store',name:'employee_store', methods: ['POST'])]
    public function store(Request $request) : Response
    {
        $employeeDto = new EmployeeRequestDto();
        $employeeDto->setName($request->get('name'));;
        $employeeDto->setEmail($request->get('email'));
        $employeeDto->setPosition($request->get('position'));

        try{
             $this->employeeService->createEmployee($employeeDto);
            $this->addFlash('Success', 'Empleado creado exitosamente');
            return $this->redirectToRoute('employee_index');
        }catch(\Exception $e){
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('employee_create');

        }

    }
    #[Route('/index',name:'employee_index', methods: ['GET'])]
    public function index()
    {

        try {
            $employees = $this->employeeService->getAllEmployees();
            return $this->render(
                'employees/index.html.twig',
                ['employees' => $employees]);
        }catch (\Exception $e){
            $this->addFlash('error', $e->getMessage());
            return $this->render('employees/index.html.twig',['employees' => []]
            );
        }
    }

    #[Route('/show/{id}',name:'employee_show', methods: ['GET'])]
    public function show(int $id)
    {
        $employee = $this->employeeService->findById($id);
        return $this->render(
            'employees/show.html.twig',
            ['employee'=>$employee]);
    }
   #[Route('/edit/{id}',name:'employee_edit', methods: ['GET'])]
   public function edit(int $id){
       $employee = $this->employeeService->findById($id);
       return $this->render(
           'employees/edit.html.twig',
           ['employee'=>$employee]);
    }
    #[Route('/delete/{id}',name:'employee_delete', methods: ['POST'])]
    public function delete(int $id)
    {
        try {
            $this->employeeService->deleteEmployee($id);
            $this->addFlash('Success','empleadx eliminadx correctamente');
            return  $this->redirectToRoute('employee_index');
        }catch (\Exception $e){
            $this->addFlash('error', 'Hubo un error al eliminar empleadx: ' . $e->getMessage());
            return   $this->redirectToRoute('employee_index');
      }
    }

    #[Route('/update/{id}',name: 'employee_update',methods: ['POST'])]
    public function update(int $id, Request $request)
    {
            $employeeRequestDto = new EmployeeRequestDto();
            $employeeRequestDto->setName($request->get('name'));
            $employeeRequestDto->setEmail($request->get('email'));
            $employeeRequestDto->setPosition($request->get('position'));

        try {
            $this->employeeService->updateEmployee($id, $employeeRequestDto);
            $this->addFlash('Success', 'editaste usuario exitosamente');
            return $this->redirectToRoute('employee_index');
        } catch (\Exception $e){
            $this->addFlash('error', 'Error al actualizar el empleado. ' . $e->getMessage());

            return  $this->redirectToRoute('employee_edit',['id'=>$id]);
        }

    }

}