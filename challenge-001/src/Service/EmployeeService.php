<?php

namespace App\Service;

use App\Dtos\EmployeeRequestDto;
use App\Dtos\EmployeeResponseDto;
use App\Entity\Employee;
use App\Entity\Project;
use App\Enums\EnumPositions;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Exception;

class EmployeeService
{
    private  $entityManager;
    private $employeeRepository;
    public function __construct(EntityManagerInterface $entityManager, EmployeeRepository $employeeRepository)
    {
        $this->entityManager = $entityManager;
        $this->employeeRepository = $employeeRepository;
    }

    public function createEmployee(EmployeeRequestDto $requestDto) : EmployeeResponseDto
    {
        $existByEmail = $this->entityManager->getRepository(Employee::class)->
        findOneBy(['email' => $requestDto->getEmail()]);
        if ($existByEmail) {
            throw new Exception("el usuario ya esta registrado");
        }
        $this->validateFields($requestDto);
        $employee = new Employee();

        $position = $requestDto->getPosition();

        try {
            $validatePosition = EnumPositions::from($position);
            $employee->setName($requestDto->getName());
            $employee->setEmail($requestDto->getEmail());
            $employee->setPosition($validatePosition->value);

            $this->entityManager->persist($employee);
            $this->entityManager->flush();
            return $this->mapToEmployeeResponseDto($employee);
        } catch (\ValueError $e) {
            throw new Exception("La posición proporcionada no es válida. Valores permitidos: " .
                implode(', ', array_map(fn($case) => $case->value, EnumPositions::cases())));
        }
    }
    private function mapToEmployeeResponseDto(Employee $employee) : EmployeeResponseDto
    {

        $employeeResponseDto = new EmployeeResponseDto();
        $employeeResponseDto->setId($employee->getId());
        $employeeResponseDto->setName($employee->getName());
        $employeeResponseDto->setEmail($employee->getEmail());
        $employeeResponseDto->setPosition($employee->getPosition());

        return $employeeResponseDto;

    }

    private function validateFields(EmployeeRequestDto $requestDto)
    {
        if(empty($requestDto->getName()) ||
            empty($requestDto->getEmail()) ||
            empty($requestDto->getPosition())
        ){
            throw new Exception("los campos no pueden estar vacios");

        }

    }

    public function getAllEmployees() : array
    {
       return $this->employeeRepository->findAllWithProjects();
    }

    public function findById(int $id) : EmployeeResponseDto
    {
      $employee  = $this->entityManager->getRepository(Employee::class)->find($id);
      if (!$employee){
          throw new Exception("Empleado no encontrado");
      }
      return $this->mapToEmployeeResponseDto($employee);
    }

    public function updateEmployee(int $id, EmployeeRequestDto $requestDto) : EmployeeResponseDto
    {
        $findEmployee = $this->entityManager->getRepository(Employee::class)->find($id);
        if(!$findEmployee){
            throw new Exception("Empleado no encontrado");
        }
        if(!empty(trim($requestDto->getName()))){
            if($requestDto->getName() !== $findEmployee->getName()){
                $findEmployee->setName($requestDto->getName());
            }
        }
        if(!empty(trim($requestDto->getEmail() && $requestDto->getEmail() !== $findEmployee->getEmail()))){

                $findEmployee->setEmail($requestDto->getEmail());

        }

        if( $requestDto->getPosition() !== $findEmployee->getPosition()){
            $findEmployee->setPosition($requestDto->getPosition());
        }
        $this->entityManager->persist($findEmployee);
        $this->entityManager->flush();

        return $this->mapToEmployeeResponseDto($findEmployee);

    }

    public function deleteEmployee(int $id)
    {
     $employee = $this->entityManager->getRepository(Employee::class)->find($id);
     if($employee){
         throw new Exception("empleado no encontrado");
     }

     try{
         $this->entityManager->remove($employee);
         $this->entityManager->flush();
     }catch (\Exception $e){
         throw new Exception("error al eliminar empleado");
     }
    }



}