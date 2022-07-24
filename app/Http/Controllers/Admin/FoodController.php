<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoodRequest;
use App\Repositories\FoodRepository;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * @var FoodRepository
     */
    private FoodRepository $foodRepository;

    /**
     * @param FoodRepository $foodRepository
     */
    public function __construct(FoodRepository $foodRepository)
    {
        $this->foodRepository = $foodRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function list(Request $request)
    {
        $result = $this->foodRepository->listAllFoods();
        return view('admin.list-food', ['listFood' => $result]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.create-food');
    }

    /**
     * @param FoodRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(FoodRequest $request)
    {
        $request = $request->all();
        $result = $this->foodRepository->createFood($request);

        if ($result) {
            return redirect()->route('list-food')->with(['status' => 'Thêm món thành công']);
        }

        return redirect()->back()->with(['status' => 'Thêm món thất bại, hãy thử lại']);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $food = $this->foodRepository->getDetailFood($id);
        return view('admin.detail-food', ['food' => $food]);
    }

    /**
     * @param FoodRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(FoodRequest $request, $id)
    {
        $request = $request->all();
        $result = $this->foodRepository->updateFood($request, $id);

        if ($result) {
            return redirect()->route('list-food')->with(['status' => 'Sửa thông tin thành công']);
        }

        return redirect()->back()->with(['status' => 'Sửa thông tin thất bại, hãy thử lại']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteEmployee($id)
    {
        $result = $this->foodRepository->deleteFood($id);

        if ($result) {
            return redirect()->route('list-food')->with(['status' => 'Đã xóa thành công']);
        }

        return redirect()->back()->with(['status' => 'Xóa thất bại, hãy thử lại']);
    }
}
