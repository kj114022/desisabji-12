<?php
/**
 * File name: CmsPageAPIController.php
 * Last modified: 2020.05.04 at 09:04:18

 *
 */

namespace App\Http\Controllers\API;



use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Repositories\CmsPageRepositoryInterface;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Support\Facades\Log;

/**
 * Class CmsPageController
 * @package App\Http\Controllers\API
 */
class CmsPageAPIController extends Controller
{
    /** @var  CmsPageRepository */
    private $cmsPageRepository;

    public function __construct(CmsPageRepositoryInterface $cmsPageRepo)
    {
        $this->cmsPageRepository = $cmsPageRepo;
    }

    /**
     * Display a listing of the CMS Pages.
     * GET|HEAD /cms-pages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::info('Fetching CMS Pages with criteria: ' . json_encode($request->all()));
        // Apply criteria to the repository
        try{
            $this->cmsPageRepository->pushCriteria(new RequestCriteria($request));
            // You can add more criteria here if needed
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        try{

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $cmsPages = $this->cmsPageRepository->all();

        return $this->sendResponse($cmsPages->toArray(), 'CMS Pages retrieved successfully');
    }

    /**
     * Display the specified CMS Page.
     * GET|HEAD /cms-pages/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var CmsPage $cmsPage */
        Log::info('Fetching CMS Page with ID: ' . $id);
        if (!empty($this->cmsPageRepository)) {
            $cmsPage = $this->cmsPageRepository->findWithoutFail($id);
        }

        if (empty($cmsPage)) {
            return $this->sendError('CMS Page not found');
        }

        return $this->sendResponse($cmsPage->toArray(), 'CMS Page retrieved successfully');
    }
}
