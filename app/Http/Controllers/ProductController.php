<?php
namespace App\Http\Controllers;
use App\Models\Product;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
		$this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $user = auth()->user();
		$user_id = $user->id;
		$products = $this->productRepository->paginateByColumn('user_id', $user_id, 5);
		// Fetch paginated products using the repository
        //$products = $this->productRepository->where('user_id', $user_id)->paginate(5);;
		return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function show($id)
    {
        
		$product = $this->productRepository->findById($id);
		//dd($product);
        return view('products.show',compact('product'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
        ]);
        $user = auth()->user();
		$user_id = $user->id;
        $product = new Product();
        $product->name = $validatedData['name'];
        $product->detail = $validatedData['detail'];
        $product->user_id = $user_id;      // Manually setting the status

        // Save the product to the database
        $product->save();
        //Product::create($request->all());
    
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }
	/**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'detail' => 'string',
        ]);

        $product = $this->productRepository->update($id, $validatedData);

        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }

    public function destroy($id)
    {
        $deleted = $this->productRepository->delete($id);

        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}
