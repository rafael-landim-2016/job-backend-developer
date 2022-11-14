<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use App\Models\Product;

class ProductsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para importar produtos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //AQUI RECEBEMOS O ID POR PARAMETRO E CHAMAMOS A FUNÇÃO
        $this->import($this->option('id'));
    }

    public function import($id = null)
    {
        $this->info("Iniciando a importação, favor aguarde.");

        //AQUI RECEBEMOS OS DADOS DA API EXTERNA
        $response = Http::get("https://fakestoreapi.com/products/$id");
        $data = json_decode($response->body());

        if($data == null){
            $this->info("Produto não encontrado");
            $this->newLine();
            exit;
        } 

        if($id != null){
            $products = [];
            array_push($products, $data); 
        }else{
            $products = $data;
        }

        //AQUI LEMOS OS PRODUTOS UM POR UM
        foreach ($products as $key => $product) {
            //AQUI VERIFICAMOS SE JA NÃO TEMOS ESTE PRODUTO CADASTRADO EM NOSSO BANCO DE DADOS
            if(Product::where('name', $product->title)->exists()) continue;

            //CASO NÃO TENHA INSERIMOS O NOVO PRODUTO
            $new_product = new Product;
            $new_product->name = $product->title;
            $new_product->price = $product->price;
            $new_product->description = $product->description;
            $new_product->category = $product->category;

            $new_product->save();
        }

        $this->info("Importação finalizada.");
        $this->newLine();
    }
}
