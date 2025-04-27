<?php

namespace App\Controller;

use App\Dtos\EmployeeRequestDto;
use App\Entity\Employee;
use App\Service\EmployeeService;
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
//
//    #[Route('/api/employees', name:'employee_create', methods: ['GET'])]
//    public function create()
//    {
//        return $this->render('employee/create.html.twig');
//    }
//    #[Route('/store',name:'employee_store', methods: ['POST'])]
//    public function store(Request $request) : Response
//    {
//        $employeeDto = new EmployeeRequestDto();
//        $employeeDto->setName($request->get('name'));;
//        $employeeDto->setEmail($request->get('email'));
//        $employeeDto->setPosition($request->get('position'));
//
//        try{
//            $employee = $this->employeeService->createEmployee($employeeDto);
//            $this->addFlash('Success', 'Empleado creado exitosamente');
//            return $this->redirectToRoute('employee_index');
//        }catch(\Exception $e){
//            $this->addFlash('error', $e->getCode());
//            return $this->redirectToRoute('employee_create');
//
//        }
//
//    }
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

}