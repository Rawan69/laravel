   
   <html>
   @extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4">Treatments</h1>

    <!-- Form to add or update treatment -->
    <form id="treatment-form" class="mb-4">
        <input type="hidden" id="treatment-id"> {{-- To hide ID when editing --}}
        <div class="mb-3">
            <label for="name" class="form-label">Treatment Name</label>
            <input type="text" class="form-control" id="name" placeholder="Enter treatment name">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" rows="3" placeholder="Enter treatment description"></textarea>
        </div>
        <button type="submit" class="btn btn-custom" id="submit-btn">Add Treatment</button>
    </form>

    <!-- Treatments Table -->
    <table class="table table-bordered table-custom">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="treatments-table">
            {{-- Data will be added here dynamically --}}
        </tbody>
    </table>
</div>

<script>
    
    function fetchTreatments() {
        fetch('{!! url("/api/treatments") !!}')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('treatments-table');
                tableBody.innerHTML = ''; 
                data.forEach(treatment => {
                    const row = `
                        <tr>
                            <td>${treatment.id}</td>
                            <td>${treatment.name}</td>
                            <td>${treatment.description}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" 
                                    onclick="editTreatment(${treatment.id}, '${treatment.name}', '${treatment.description}')">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteTreatment(${treatment.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => {
                console.error('Error fetching treatments:', error);
            });
    }

    
    document.getElementById('treatment-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const treatmentId = document.getElementById('treatment-id').value;
        const treatmentData = {
            name: document.getElementById('name').value,
            description: document.getElementById('description').value
        };

        let url = "{{ url('/api/treatments') }}".replace(/&amp;/g, '&');
        let method = 'POST';

        if (treatmentId) {
            url += `/${treatmentId}`;
            method = 'PUT'; 
        }

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(treatmentData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Treatment added/updated:', data);
            fetchTreatments(); 
            document.getElementById('treatment-form').reset(); 
            document.getElementById('submit-btn').textContent = 'Add Treatment'; 
            document.getElementById('treatment-id').value = ''; 
        })
        .catch(error => {
            console.error('Error adding/updating treatment:', error);
        });
    });

    
    function editTreatment(id, name, description) {
        document.getElementById('treatment-id').value = id;
        document.getElementById('name').value = name;
        document.getElementById('description').value = description;
        document.getElementById('submit-btn').textContent = 'Update Treatment'; // تغيير النص
    }

    function deleteTreatment(id) {
        fetch(`{{ url('/api/treatments') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(() => fetchTreatments()) 
        .catch(error => console.error('Error deleting treatment:', error));
    }

   
    fetchTreatments();
</script>


@endsection


</html>



