<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\UpdateFavoriteRequest;
use App\Models\Favorite;
use App\Repositories\FavoriteRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Criteria\Users\FilterByUserCriteria;
use Illuminate\Support\Facades\Log;

/**
 * Class FavoriteController
 * @package App\Http\Controllers\API
 */

class FavoriteAPIController extends Controller
{
    /** @var  FavoriteRepository */
    private $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepo)
    {
        $this->favoriteRepository = $favoriteRepo;
    }

    /**
     * Display a listing of the Favorite.
     * GET|HEAD /favorites
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::info('Fetching Favorites with criteria: ' . json_encode($request->all()));
        // Apply criteria to the repository
        try{
            $this->favoriteRepository->pushCriteria(new RequestCriteria($request));
            if ($request->has('user_id')) {
                $this->favoriteRepository->pushCriteria(new FilterByUserCriteria($request->get('user_id')));
                $this->favoriteRepository->join('products', 'favorites.product_id', '=', 'products.id')
                ->join('product_cities', 'products.id', '=', 'product_cities.product_id')
                ->where('product_cities.city_id', $request->get('city_id', null))
                ->select('favorites.*', 'products.name as product_name', 'products.description as product_description', 'products.price as product_price', 'products.product_image as product_image', 'products.product_icon as product_icon');
            }
            
           // $this->favoriteRepository->pushCriteria(new LimitOffsetCriteria($request));

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $favorites = $this->favoriteRepository->all();

        return $this->sendResponse($favorites->toArray(), 'Favorites retrieved successfully');
    }


    /**
     * Display the specified Favorite.
     * GET|HEAD /favorites/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Favorite $favorite */
        Log::info('Fetching Favorite with ID: ' . $id);
        if (!empty($this->favoriteRepository)) {
            $favorite = $this->favoriteRepository->findWithoutFail($id);
        }

        if (empty($favorite)) {
            return $this->sendError('Favorite not found');
        }

        return $this->sendResponse($favorite->toArray(), 'Favorite retrieved successfully');
    }

    /**
     * Store a newly created Favorite in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Storing a new Favorite with input: ' . json_encode($request->all()));
        // Validate the request data
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'options' => 'array',
        ]);
        $input = $request->all();
        try {
            $favorite = $this->favoriteRepository->updateOrCreate($request->only('user_id','product_id','options'),$input);
        } catch (ValidatorException $e) {
            return $this->sendError('Favorite not found');
        }

        return $this->sendResponse($favorite->toArray(), __('lang.saved_successfully',['operator' => __('lang.favorite')]));
    }

    /**
     * Store a newly created Favorite in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exist(Request $request)
    {
        $input = $request->only('product_id','user_id');
        try {
            $favorites = $this->favoriteRepository->findWhere($input);
        } catch (ValidatorException $e) {
            return $this->sendError('Favorite not found');
        }

        return $this->sendResponse($favorites->first(), __('lang.saved_successfully',['operator' => __('lang.favorite')]));
    }

    /**
     * Update the specified Favorite in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        Log::info('Updating Favorite with ID: ' . $id . ' and input: ' . json_encode($request->all()));
        // Validate the request data
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'options' => 'array',
        ]);
        // Find the favorite by ID
        $favorite = $this->favoriteRepository->findWithoutFail($id);

        if (empty($favorite)) {
            return $this->sendError('Favorite not found');
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->favoriteRepository->model());
        try {
            $favorite = $this->favoriteRepository->update($input, $id);
            $input['options'] = isset($input['options']) ? $input['options'] : [];

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $favorite->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($favorite->toArray(),__('lang.updated_successfully', ['operator' => __('lang.favorite')]));

    }

    /**
     * Remove the specified Favorite from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Log::info('Deleting Favorite with ID: ' . $id);
        // Find the favorite by ID
   
        $favorite = $this->favoriteRepository->findWhere([
            'id' => $id
        ])->first();
        if (empty($favorite)) {
            Log::error('Favorite not found', ['id' => $id]);
             $response = [
                'message' => 'Favorite not found',
                'favorite' => $id
            ];
           // Log::error('Favorite not found', ['id' => $id]);
            return $response;
        }

        $this->favoriteRepository->delete($id);

        $response = [
            'message' => __('lang.deleted_successfully', ['operator' => __('lang.favorite')]),
            'favorite' => $favorite->id
        ];
        return $response;

    }
}
