<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\EditFoodRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function list(Request $request)
    {
        $result = $this->userRepository->listAllEmployee();
        return view('admin.list-employee', ['listEmployee' => $result]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.create-employee');
    }

    /**
     * @param CreateEmployeeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(CreateEmployeeRequest $request)
    {
        $request = $request->all();
        $result = $this->userRepository->createEmployee($request);

        if ($result) {
            return redirect()->route('list-employee')->with(['status' => 'Thêm nhân viên thành công']);
        }

        return redirect()->back()->with(['status' => 'Thêm nhân viên thất bại, hãy thử lại']);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $employee = $this->userRepository->getDetailEmployee($id);
        return view('admin.detail-employee', ['employee' => $employee]);
    }

    /**
     * @param EditFoodRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(EditFoodRequest $request, $id)
    {
        $request = $request->all();

        $result = $this->userRepository->updateEmployee($request, $id);

        if ($result) {
            return redirect()->route('list-employee')->with(['status' => 'Sửa thông tin thành công']);
        }

        return redirect()->back()->with(['status' => 'Sửa thông tin thất bại, hãy thử lại']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteEmployee($id)
    {
        $result = $this->userRepository->deleteEmployee($id);

        if ($result) {
            return redirect()->route('list-employee')->with(['status' => 'Đã xóa thành công']);
        }

        return redirect()->back()->with(['status' => 'Xóa thất bại, hãy thử lại']);
    }
}
