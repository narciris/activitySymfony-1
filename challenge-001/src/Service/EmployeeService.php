<?php

namespace App\Service;

use App\Dtos\EmployeeRequestDto;
use App\Dtos\EmployeeResponseDto;
use App\Entity\Employee;
use App\Enums\EnumPositions;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Exception;

class EmployeeService
{
    private  $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createEmployee(EmployeeRequestDto $requestDto) : EmployeeResponseDto
    {
        $existByEmail = $this->entityManager->getRepository(Employee::class)->
        findOneBy(['email' => $requestDto->getEmail()]);
        if ($existByEmail) {
            throw new Exception("el usuario ya esta registrado");
        }
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

    public function getAllEmployees()
    {
       return $this->entityManager->getRepository(Employee::class)->findAll();
    }

}