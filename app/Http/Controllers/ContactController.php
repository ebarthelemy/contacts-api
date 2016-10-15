<?php namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request['ordering']) {
            $ordering = $request['ordering'];
            switch ($ordering) {
                case 'name':
                    $field = 'name';
                    $order = 'ASC';
                    break;
                case '-name':
                    $field = 'name';
                    $order = 'DESC';
                    break;
                case 'email':
                    $field = 'email';
                    $order = 'ASC';
                    break;
                case '-email':
                    $field = 'email';
                    $order = 'DESC';
                    break;
                default:
                    $field = 'name';
                    $order = 'ASC';
            }
        }

        if ($request['search']) {
            $search = $request['search'];
        }

        if (isset($ordering) && isset($search)) {
            $contacts = DB::table('contacts')
                ->where('name', 'LIKE', '%' . $search . '%')
                ->orderBy($field, $order)
                ->paginate(20);
            return $this->createSuccessResponse($contacts, 200);
        }

        if (isset($ordering) && !isset($search)) {
            $contacts = DB::table('contacts')
                ->orderBy($field, $order)
                ->paginate(20);
            return $this->createSuccessResponse($contacts, 200);
        }

        if (!isset($ordering) && isset($search)) {
            $contacts = DB::table('contacts')
                ->where('name', 'like', $search)
                ->paginate(20);
            return $this->createSuccessResponse($contacts, 200);
        }

        $contacts = Contact::paginate(20);
        return $this->createSuccessResponse($contacts, 200);
    }

    /**
     * Store a newly created contact in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $input = $request->all();

        $input['id'] = Uuid::generate();

        $contact = Contact::create($input);

        return $this->createSuccessResponse("{$contact->name} has been created.", 201);
    }

    /**
     * Display the specified contact.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return $this->createErrorResponse("The contact does not exists.", 404);

        }

        return $this->createSuccessResponse($contact, 200);
    }

    /**
     * Update the specified contact in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return $this->createErrorResponse("The contact does not exists.", 404);
        }

        $this->validateRequest($request);

        $contact->name = $request->get('name');
        $contact->sex = $request->get('sex');
        $contact->birthday = $request->get('birthday');
        $contact->email = $request->get('email');
        $contact->phone = $request->get('phone');
        $contact->address = $request->get('address');
        $contact->city = $request->get('city');
        $contact->postcode = $request->get('postcode');
        $contact->country = $request->get('country');
        $contact->photo = $request->get('photo');
        $contact->favorite = $request->get('favorite');
        $contact->save();

        return $this->createSuccessResponse("{$contact->name} has been updated.", 200);
    }

    /**
     * Remove the specified contact from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return $this->createErrorResponse("The contact does not exists.", 404);
        }

        $name = $contact->name;

        $contact->delete();

        return $this->createSuccessResponse("{$name} has been deleted.", 200);
    }

    function validateRequest($request)
    {
        $rules = [
            'name' => 'required',
            'sex' => 'required|in:m,f',
            'birthday' => 'required|date',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postcode' => '',
            'country' => 'required',
            'photo' => 'required',
            'favorite' => 'boolean',
        ];

        $this->validate($request, $rules);
    }
}
