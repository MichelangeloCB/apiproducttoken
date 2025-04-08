<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Exception;

class Produtos extends ResourceController {
    private $produtosModel; // Nome ajustado para corresponder ao modelo correto
    private $token = '1234567890abdefghi';

    public function __construct()
    {
        $this->produtosModel = new \App\Models\ProdutosModel();
    }

    private function _validaToken()
    {
        return $this->request->getHeaderLine('token') == $this->token;
    }

    // Serviço para retornar todos os produtos (GET)
    public function list()
    {
        $data = $this->produtosModel->findAll();

        return $this->response->setJSON($data);
    }

    // Serviço para inserir um novo produto (POST)
    public function create()
    {
        $response = [];

        // Validar o token
        if ($this->_validaToken() == true) {
            // Pegar os dados (que vieram no body da requisição) para salvar
            $newProduto['nome'] = $this->request->getPost('nome');
            $newProduto['valor'] = $this->request->getPost('valor');

            try {
                if ($this->produtosModel->insert($newProduto)) { // Corrigido para $this->produtosModel
                    // Deu certo
                    $response = [
                        'response' => 'sucess',
                        'msg' => 'Produto adicionado com sucesso!'
                    ];
                } else {
                    $response = [
                        'response' => 'error',
                        'msg' => 'Erro ao salvar produto',
                        'errors' => $this->produtosModel->errors() // Corrigido para $this->produtosModel
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'response' => 'error',
                    'msg' => 'Erro ao salvar produto',
                    'errors' => [
                        'exception' => $e->getMessage()
                    ]
                ];
            }
        } else {
            $response = [
                'response' => 'error',
                'msg' => 'Token inválido',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function update($id = null)
    {
        $response = [];

        // Validar o Token!
        if ($this->_validaToken() == true) {
            try{
                // Verificar se o produto existe
                $produto = $this->produtosModel->find($id);
                if ($produto) {
                    //obter os dados enviados na requisição
                    $updatedData = [
                        'nome' => $this->request->getPost('nome'),
                        'valor' => $this->reuest->getPost('valor')
                    ];

                    // Atualizar o produto
                    if ($this->produtosModel->update($id, $updatedData)) {
                        $response = [
                            'response' => 'sucess',
                            'msg' => 'Produto atualizado com sucesso!'
                        ];
                    } else {
                        $response = [
                            'response' => 'error',
                            'msg' => 'Erro ao atualizar o produto',
                            'errors' => $this->produtosModel->errors()
                        ];
                    }
                }else {
                    $response = [
                        'response' => 'error',
                        'msg' => 'Produto não encontrado!'
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'response' => 'error',
                    'msg' => 'Erro ao atualizar o produto',
                    'errors' => [
                        'exception' => $e->getMessage()
                    ]
                    ];
            }
        }else {
            $response = [
                'response' => 'error',
                'msg' => 'Token inválido',
            ];
        }

        return $this->response->setJSON($response);
    }
    
  public function delete($id = null)
{
    $response = [];

    // Validar o token
    if ($this->_validaToken() == true) {
        try {
            // Verificar se o produto existe
            $produto = $this->produtosModel->find($id);
            if ($produto) {
                // Deletar o produto
                if ($this->produtosModel->delete($id)) {
                    $response = [
                        'response' => 'success',
                        'msg' => 'Produto excluído com sucesso!'
                    ];
                } else {
                    $response = [
                        'response' => 'error',
                        'msg' => 'Erro ao excluir o produto'
                    ];
                }
            } else {
                $response = [
                    'response' => 'error',
                    'msg' => 'Produto não encontrado'
                ];
            }
        } catch (Exception $e) {
            $response = [
                'response' => 'error',
                'msg' => 'Erro ao excluir o produto',
                'errors' => [
                    'exception' => $e->getMessage()
                ]
            ];
        }
    } else {
        $response = [
            'response' => 'error',
            'msg' => 'Token inválido',
        ];
    }

    return $this->response->setJSON($response);
}
}