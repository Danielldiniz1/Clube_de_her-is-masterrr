<?php

namespace Source\App\Api;

use Source\Models\Club;

class Clubs extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getClub()
    {
        $this->auth();

        $clubs = new Club();
        $userClubs = $clubs->selectByUserId($this->userAuth->id);

        $this->back([
            "tipo" => "successo",
            "mensagem" => "Clubes do usuário",
            "clubs" => $userClubs
        ]);
    }

    public function listClubs()
    {
        $clubs = new Club();
        // No admin, vamos listar todos, não apenas os ativos
        $allClubs = $clubs->selectAll() ?? [];

        $this->call(200, "success", "Lista de clubes recuperada", "success")->back([
            "clubs" => $allClubs
        ]);
    }

    public function createClub(array $data)
    {
        $club = new Club(
            null,
            $data["user_id"] ?? null,
            $data["club_name"] ?? null,
            $data["description"] ?? null
        );

        $insertId = $club->insert();

        if(!$insertId){
            $this->call(400, "error", $club->getMessage(), "error")->back();
            return;
        }

        $this->call(201, "success", "Clube cadastrado com sucesso!", "success")->back([
            "club_id" => $insertId
        ]);
    }

    public function updateClub(array $data)
    {
        if(empty($data["id"])) {
            $this->call(400, "error", "ID do clube é obrigatório", "error")->back();
            return;
        }

        $clubs = new Club();
        $clubData = $clubs->selectById($data["id"]);

        if(!$clubData) {
            $this->call(404, "error", "Clube não encontrado", "error")->back();
            return;
        }

        $club = new Club(
            $data["id"],
            $clubData->user_id,
            $data["club_name"] ?? $clubData->club_name,
            $data["description"] ?? $clubData->description,
            isset($data["is_active"]) ? filter_var($data["is_active"], FILTER_VALIDATE_BOOLEAN) : $clubData->is_active
        );

        if(!$club->update()){
            $this->call(400, "error", $club->getMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", $club->getMessage(), "success")->back(["club" => (array)$clubData]);
    }

    public function getClubById(array $data)
    {
        if(empty($data["id"])) {
            $this->call(400, "error", "ID do clube é obrigatório", "error")->back();
            return;
        }

        $clubs = new Club();
        $club = $clubs->selectById($data["id"]);

        if(!$club) {
            $this->call(404, "error", "Clube não encontrado", "error")->back();
            return;
        }

        $this->call(200, "success", "Dados do clube recuperados", "success")->back([
            "club" => $club
        ]);
    }

    public function deleteClub(array $data)
    {
        if(empty($data["id"])) {
            $this->call(400, "error", "ID do clube é obrigatório", "error")->back();
            return;
        }

        $clubs = new Club();
        $clubData = $clubs->selectById($data["id"]);

        if(!$clubData) {
            $this->call(404, "error", "Clube não encontrado", "error")->back();
            return;
        }

        $club = new Club($data["id"]);

        if(!$club->delete()){
            $this->call(500, "error", $club->getMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Clube removido com sucesso!", "success")->back();
    }
}
