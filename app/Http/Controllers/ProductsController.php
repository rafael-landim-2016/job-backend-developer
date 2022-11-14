<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
/**
 * @OA\Info(title="Api Loja de Produtos",  version="0.1")
 */
class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
    * @OA\Get(
    * path="/api/products",
    * summary="Todos os produtos",
    * description="Retorna todos os produtos",
    * tags={"Listagem"},
    * @OA\Response(
    *         response="200",
    *         description="OK, retorna uma lista de produtos.",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     example=
    *                     {
    *                         {
    *                             "id": "1",
    *                             "name": "Curso de Laravel",
    *                             "price": 160,
    *                             "description": "Curso de 60 horas",
    *                             "category": "Cursos",
    *                             "image_url": null,
    *                             "created_at": "2022-11-12T19:50:30.000000Z",
    *                             "updated_at": "2022-11-12T19:50:30.000000Z"
    *                         },
    *                         {
    *                             "id": "2",
    *                             "name": "Curso de Flutter",
    *                             "price": 200,
    *                             "description": "Curso de 36 horas",
    *                             "category": "Cursos",
    *                             "image_url": null,
    *                             "created_at": "2022-11-12T19:50:30.000000Z",
    *                             "updated_at": "2022-11-12T19:50:30.000000Z"
    *                         },
    *                    }
    *                 )
    *             )
    *         }
    *     ),
    * )
    * @OA\Get(
    * path="/api/products/{id} ",
    * summary="Pegar um produto por id",
    * description="Retorna o produto com o id pesquisado",
    * tags={"Listagem"},
    * @OA\Parameter(
    *    name="id",
    *    in="path",
    *    required=true,
    *    description="ID do produto",
    *    @OA\Schema(
    *        type="integer"
    *       ),
    *     ),
    * @OA\Response(
    *         response="200",
    *         description="OK, retorna um objeto com os dados do produto.",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     example=
    *                         {
    *                             "id": "1",
    *                             "name": "Curso de Laravel",
    *                             "price": 160,
    *                             "description": "Curso de 60 horas",
    *                             "category": "Cursos",
    *                             "image_url": null,
    *                             "created_at": "2022-11-12T19:50:30.000000Z",
    *                             "updated_at": "2022-11-12T19:50:30.000000Z"
    *                         }
    *                 )
    *             )
    *         }
    *     ),
    *   @OA\Response(response="404",description="Não existe um produto com o id informado."),

    * )
    */

    public function index(Request $filters)
    {
        //ESTE MÉTODO RETORNA TODOS OS PRODUTOS
        //TAMBÉM PODEMOS RECEBER OS FILTROS POR PARAMETRO CASO A REQUISIÇÃO SEJA UMA BUSCA
        //OS DADOS DE FILTRO DEVEM SER ENVIADOS DA SEGUINTE FORMA
        // {
        //     NOME_DA_COLUNA : VALOR_PROCURADO,
        //     NOME_DA_COLUNA : VALOR_PROCURADO,
        // }
        try {
            $data_filter = $filters->all();
            unset($data_filter['_method']);

            if(!empty($data_filter)){
                //CASO TENHAMOS FILTROS FAZEMOS UMA QUERY COM CADA UM DELES
                $products = Product::query();
                foreach ($data_filter as $key => $value) {
                    $products->where($key, 'LIKE', "%$value%");
                }
                //RETORNAMOS OS RESULTADOS ENCONTRADOS
                return response()->json($products->get(), 200);
            }else{
                //CASO NÃO TENHAMOS FILTROS RETORNAMOS TODOS OS PRODUTOS
                return response()->json(Product::all(), 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            abort(500, "Ocorreu um erro ao buscar pelos produtos, verifique os filtros e tente novamente");
        }

    }

    /**
    * @OA\Put(
    * path="/api/products",
    * summary="Buscar produtos com filtro",
    * description="Retorna todos os produtos com a possibilidade de filtrar por qualquer item da tabela",
    * tags={"Listagem"},
    * @OA\RequestBody(
    *    required=false,
    *    description="Informações do filtro",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", format="string", example="Laptops"),
     *       @OA\Property(property="price", type="string", format="string", example="109.95"),
     *    ),
    * ),
    * @OA\Response(
    *         response="200",
    *         description="OK, retorna uma lista de produtos.",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     example=
    *                     {
    *                         {
    *                             "id": "1",
    *                             "name": "Curso de Laravel",
    *                             "price": 160,
    *                             "description": "Curso de 60 horas",
    *                             "category": "Cursos",
    *                             "image_url": null,
    *                             "created_at": "2022-11-12T19:50:30.000000Z",
    *                             "updated_at": "2022-11-12T19:50:30.000000Z"
    *                         },
    *                         {
    *                             "id": "2",
    *                             "name": "Curso de Flutter",
    *                             "price": 200,
    *                             "description": "Curso de 36 horas",
    *                             "category": "Cursos",
    *                             "image_url": null,
    *                             "created_at": "2022-11-12T19:50:30.000000Z",
    *                             "updated_at": "2022-11-12T19:50:30.000000Z"
    *                         },
    *                    }
    *                 )
    *             )
    *         }
    *     ),
    *   @OA\Response(response="500",description="Ocorreu um erro ao buscar pelos produtos, verifique os filtros e tente novamente"),
    * )
    */

    public function withoutPhoto()
    {
        //NESTE MÉTODO RETORNAMOS TODOS OS PRODUTOS SEM IMAGEM
        return response()->json(Product::whereNull('image_url')->orWhere('image_url', '')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
    * path="/api/get-products-without-photo",
    * summary="Todos os produtos que não tem foto",
    * description="Retorna todos os produtos sem foto",
    * tags={"Listagem"},
    * @OA\Response(
    *         response="200",
    *         description="OK, retorna uma lista de produtos.",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     example=
    *                     {
    *                         {
    *                             "id": "1",
    *                             "name": "Curso de Laravel",
    *                             "price": 160,
    *                             "description": "Curso de 60 horas",
    *                             "category": "Cursos",
    *                             "image_url": null,
    *                             "created_at": "2022-11-12T19:50:30.000000Z",
    *                             "updated_at": "2022-11-12T19:50:30.000000Z"
    *                         },
    *                         {
    *                             "id": "2",
    *                             "name": "Curso de Flutter",
    *                             "price": 200,
    *                             "description": "Curso de 36 horas",
    *                             "category": "Cursos",
    *                             "image_url": null,
    *                             "created_at": "2022-11-12T19:50:30.000000Z",
    *                             "updated_at": "2022-11-12T19:50:30.000000Z"
    *                         },
    *                    }
    *                 )
    *             )
    *         }
    *     ),
    * )
     */
    public function store(ProductRequest $request)
    {
        //NESTE MÉTODO FAZEMOS A INSERÇÃO DE UM NOVO PRODUTO NO BANCO DE DADOS
        try {
            //AQUI ENVIAMOS OS DADOS PARA A MODEL E CRIAMOS O NOVO PRODUTO
            $produto = Product::create($request->all());
            //RETORNAMOS O NOVO PRODUTO
            return response()->json($produto, 200);
        } catch (\Throwable $th) {
            //throw $th;
            abort(500, "Não foi possível salvar o produto, verifique os dados enviados e tente novamente.");
        }
    }

    /**
     * * @OA\Post(
    * path="/api/products",
    * summary="Inserir um novo produto",
    * description="Insere um novo produto no banco de dados",
    * tags={"Criação"},
    * @OA\RequestBody(
    *    required=false,
    *    description="Informações do filtro",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", format="string", example="Nome do produto aqui"),
     *       @OA\Property(property="price", type="float", format="float", example="199.99"),
     *       @OA\Property(property="description", type="text", format="text", example="Descrição do produto aqui"),
     *       @OA\Property(property="category", type="string", format="string", example="Categoria do produto aqui"),
     *       @OA\Property(property="image_url", type="string", format="string", example="https://fakestoreapi.com/img/51UDEzMJVpL._AC_UL640_QL65_ML3_.jpg"),
     *    ),
    * ),
    * @OA\Response(
    *         response="200",
    *         description="OK, retorna o produto cadastrado.",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     example=
    *                     {
*                             "id": "1",
*                             "name": "Curso de Laravel",
*                             "price": 160,
*                             "description": "Curso de 60 horas",
*                             "category": "Cursos",
*                             "image_url": null,
*                             "created_at": "2022-11-12T19:50:30.000000Z",
*                             "updated_at": "2022-11-12T19:50:30.000000Z"
*                         },
    *                 )
    *             )
    *         }
    *     ),
    *   @OA\Response(response="422",description="Erros de validação, verifique a resposta."),
    *   @OA\Response(response="500",description="Ocorreu um erro ao cadastrar o produto."),
    * )
    *
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //NESTE MÉTODO VOCÊ PODE BUSCAR UM PRODUTO POR UM ID ESPECÍFICO, RETORNA 404 CASO NÃO ENCONTRE UM PRODUTO COM O ID
        return response()->json(Product::findOrFail($id), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    * @OA\Put(
    * path="/api/products/{id}",
    * summary="Editar um produto",
    * description="Editar um produto no banco de dados",
    * tags={"Atualização"},
    * @OA\Parameter(
    *    name="id",
    *    in="path",
    *    required=true,
    *    description="ID do produto",
    *    @OA\Schema(
    *        type="integer"
    *       ),
    *     ),
    * @OA\RequestBody(
    *    required=false,
    *    description="Informações do filtro",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", format="string", example="Nome do produto aqui"),
     *       @OA\Property(property="price", type="float", format="float", example="199.99"),
     *       @OA\Property(property="description", type="text", format="text", example="Descrição do produto aqui"),
     *       @OA\Property(property="category", type="string", format="string", example="Categoria do produto aqui"),
     *       @OA\Property(property="image_url", type="string", format="string", example="https://fakestoreapi.com/img/51UDEzMJVpL._AC_UL640_QL65_ML3_.jpg"),
     *    ),
    * ),
    * @OA\Response(
    *         response="200",
    *         description="OK, retorna o produto cadastrado.",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     example=
    *                     {
*                             "id": "1",
*                             "name": "Curso de Laravel",
*                             "price": 160,
*                             "description": "Curso de 60 horas",
*                             "category": "Cursos",
*                             "image_url": null,
*                             "created_at": "2022-11-12T19:50:30.000000Z",
*                             "updated_at": "2022-11-12T19:50:30.000000Z"
*                         },
    *                 )
    *             )
    *         }
    *     ),
    *   @OA\Response(response="422",description="Erros de validação, verifique a resposta."),
    *   @OA\Response(response="500",description="Ocorreu um erro ao cadastrar o produto."),
    * )
     */
    public function update(ProductRequest $request, $id)
    {
        return $request->all();
        //ESTE MÉTODO FAZ UMA ATUALIZAÇÃO NOS DADOS DO PRODUTO

        //CASO NÃO ENCONTRE UM PRODUTO COM O ID INFORMADO, VAMOS RETORNAR UM ERRO 404
        if(!Product::where('id', $id)->exists()) abort(404, "Não foi encontrado um produto com este ID.");

        try {
            //RECEBEMOS OS DADOS ENVIADOS
            $data_update = $request->all();
            //REMOVENDO O ATRIBUTO _method CASO SEJA NECESSÁRIO ENVIAR PELO POSTMAN/INSOMINIA POIS NÃO EXISTE UMA COLUNA COM ESTE NOME
            unset($data_update['_method']);
            //FAZEMOS A ATUALIZAÇÃO DOS DADOS
            $produto = Product::where('id', $id)->update($data_update);

            //RETORNAMOS O PRODUTO COM OS NOVOS DADOS
            return response()->json(Product::find($id), 200);
        } catch (\Throwable $th) {
            //throw $th;
            //CASO OCORRA ALGUM ERRO NESTE PROCESSO RETORNAMOS UM ERRO 500
            abort(500, "Não foi possível atualizar o produto, verifique os dados enviados e tente novamente.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Delete(
    * path="/api/products/{id}",
    * summary="Buscar produtos com filtro",
    * description="Retorna todos os produtos com a possibilidade de filtrar por qualquer item da tabela",
    * tags={"Exclusão"},
    * @OA\Parameter(
    *    name="id",
    *    in="path",
    *    required=true,
    *    description="ID do produto",
    *    @OA\Schema(
    *        type="integer"
    *       ),
    *     ),
    * @OA\Response(
    *         response="200",
    *         description="OK, retorna uma mensagem de sucesso.",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     example="Produto deletado com sucesso"
    *                 )
    *             )
    *         }
    *     ),
    *   @OA\Response(response="404",description="Produto não encontrado na base de dados."),
    * )
     */
    public function destroy($id)
    {
        //ESTE MÉTODO FAZ A EXCLUSÃO DE UM PRODUTO NO BANCO DE DADOS
        $product = Product::findOrFail($id);
        //CASO NÃO ENCONTRE UM PRODUTO COM O ID INFORMADO, RETORNAMOS 404

        try {
            // DELETAMOS O PRODUTO E RETORNAMOS O SUCESSO
            if($product->delete()) return response()->json("Produto deletado com sucesso", 200);
        } catch (\Throwable $th) {
            // CASO ALGO DÊ ERRADO VAMOS RETORNAR UM ERRO 500
            abort(500, "Ocorreu um erro ao deletar o produto");
        }
    }
}
