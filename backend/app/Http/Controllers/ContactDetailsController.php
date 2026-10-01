<?php

namespace App\Http\Controllers;

use App\Services\ContactDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactDetailsController extends Controller
{
    private function scope(string $scope): int
    {
        if ($scope === 'default') return 0;
        abort_unless(ctype_digit($scope) && (int) $scope > 0 && DB::table('journals')->where('j_id', $scope)->exists(), 404);
        return (int) $scope;
    }

    public function index(string $scope, ContactDetails $contacts)
    {
        $id = $this->scope($scope);
        return response()->json([
            'contact' => $id ? array_replace(array_fill_keys(['address','email','phone'], ''), $contacts->stored($id)) : $contacts->resolve(),
            'defaults' => $contacts->resolve(),
            'journals' => DB::table('journals')->orderBy('j_name')->get(['j_id','j_name','slug']),
        ]);
    }

    public function published(string $scope, ContactDetails $contacts)
    {
        return response()->json(['contact' => $contacts->resolve($this->scope($scope))]);
    }

    public function update(Request $request, string $scope, ContactDetails $contacts)
    {
        $id = $this->scope($scope);
        $required = $id ? 'nullable' : 'required';
        $data = $request->validate([
            'address' => ['present', $required, 'string', 'max:2000'],
            'email' => ['present', $required, 'email:rfc', 'max:255'],
            'phone' => ['present', $required, 'string', 'max:80', 'regex:/^[+0-9().\s\-]+$/D'],
        ]);
        DB::table('contact_details')->upsert([
            ['journal_id' => $id] + $data + ['created_at'=>now(), 'updated_at'=>now()],
        ], ['journal_id'], ['address','email','phone','updated_at']);
        return $this->index($scope, $contacts);
    }

    public function destroy(string $scope, ContactDetails $contacts)
    {
        $id = $this->scope($scope);
        abort_unless($id, 422, 'The default contact cannot be removed.');
        DB::table('contact_details')->where('journal_id', $id)->delete();
        return $this->index($scope, $contacts);
    }
}
