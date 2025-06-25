<x-app-layout>
    <style>
        
        h1 {
            color: #c34a64;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
        }
       

        .form-group label {
            font-size: 1rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .form-control {
            border: 2px solid #c34a64;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 5px rgba(211, 47, 47, 0.5);
        }

        .form-control::placeholder {
            color: #888;
        }

        .btn-primary {
            background-color: #c34a64;
            border-color: #c34a64;
            color: #fff;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #D32F2F;
            border-color: #D32F2F;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-danger ul {
            list-style-type: none;
            padding: 0;
        }

        .alert-danger li {
            margin: 5px 0;
        }

        select.form-control {
            height: 40px;
        }

        textarea.form-control {
            height: 150px;
            resize: vertical;
        }
        .div-flex{
            display: flex;
            gap: 67px;
            justify-content: center;
        }
        #status{
            position: absolute;
    width: auto;
    margin-top: 20px !important;
}
  

  
/* Global Style */
body {
  font-family: Arial, sans-serif;
  background-color: #f5f5f5;
  margin: 0;
  padding: 0;
}

/* Card Container */
.form-card {
  max-width: 600px;
  margin: 50px auto;
  background: #cedce7;
    background: -webkit-linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
    background: -o-linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
    background: linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
    background: linear-gradient(178deg, #ff8080, #fdfcfc, #ff0e0e, #d53070);
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 20px;
  overflow: hidden;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.form-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

/* Form Group */
.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: #333;
}

input,
textarea,
select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  box-sizing: border-box;
}

textarea {
  resize: vertical;
}

/* Button */
.btn-primary {
  background-color: #e63946;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #d62839;
}

/* Flex Container */
.div-flex {
  display: flex;
  gap: 15px;
}

/* Animation */
.animated-form {
  animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
  form{
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
    @can("vente-edit")
    <div class="container">
        <h1>Modifier un Vente</h1>
        <div class="form-card">
        <form action="{{ route('vente_objectifs.update', $venteObjectif->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="div-flex">
                <div class="form-group">
                    <label for="Quantitépc">Quantité PC</label>
                    <input type="number" id="Quantitépc" name="Quantitépc" class="form-control" value="{{ $venteObjectif->Quantitépc }}" required oninput="calculateTotalAchat(); calculateEnStock(); calculateMarge();">
                </div>

                <div class="form-group">
                    <label for="prixachat">Prix Achat</label>
                    <input type="text" id="prixachat" name="prixachat" class="form-control" value="{{ $venteObjectif->prixachat }}" required oninput="calculateTotalAchat(); calculateMarge();">
                </div>

                <div class="form-group">
                    <label for="totalachat">Total Achat</label>
                    <input type="text" id="totalachat" name="totalachat" class="form-control" value="{{ $venteObjectif->totalachat }}" required readonly>
                </div>
            </div>

            <div class="div-flex">
            <div class="form-group">
                <label for="prixvendu">Prix Vendu</label>
                <input type="text" id="prixvendu" name="prixvendu" class="form-control" value="{{ $venteObjectif->prixvendu }}" required oninput="calculateTotalVendu(); calculateMarge();">
            </div>


            <div class="form-group">
                <label for="Quantitévendu">Quantité Vendu</label>
                <input type="number" id="Quantitévendu" name="Quantitévendu" class="form-control" value="{{ $venteObjectif->Quantitévendu }}" required oninput="calculateTotalVendu(); calculateEnStock(); calculateMarge();">
            </div>
    
            <div class="form-group">
                <label for="totalvendu">Total Vendu</label>
                <input type="text" id="totalvendu" name="totalvendu" class="form-control" value="{{ $venteObjectif->totalvendu }}" required readonly>
            </div>
            </div>
            <div class="div-flex">
            <div class="form-group">
                <label for="marge">Marge</label> <!-- Corrected label -->
                <input type="text" id="marge" name="marge" class="form-control" value="{{ $venteObjectif->marge }}" required readonly>
            </div>

            
            <div class="form-group">
                <label for="enstock">En Stock</label>
                <input type="number" id="enstock" name="enstock" class="form-control" value="{{ $venteObjectif->enstock }}" required readonly>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $venteObjectif->date }}" required>
            </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" required>{{ $venteObjectif->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" id="updateButton">Mettre à jour</button>
            {{-- <a href="{{ route('vente_objectifs.index') }}" class="btn btn-secondary">Retour</a> --}}
        </form>
    </div>
    
    <script>
        function calculateTotalAchat() {
            const quantitépc = document.getElementById('Quantitépc').value;
            const prixachat = document.getElementById('prixachat').value;
            const totalachat = document.getElementById('totalachat');

            if (quantitépc && prixachat) {
                totalachat.value = (quantitépc * prixachat).toFixed(2);
            } else {
                totalachat.value = '';
            }
            calculateMarge();
            calculateEnStock();
        }

        function calculateTotalVendu() {
            const quantitévendu = document.getElementById('Quantitévendu').value;
            const prixvendu = document.getElementById('prixvendu').value;
            const totalvendu = document.getElementById('totalvendu');

            if (quantitévendu && prixvendu) {
                totalvendu.value = (quantitévendu * prixvendu).toFixed(2);
            } else {
                totalvendu.value = '';
            }
            calculateMarge();
            calculateEnStock();
        }

        function calculateEnStock() {
            const quantitépc = document.getElementById('Quantitépc').value;
            const quantitévendu = document.getElementById('Quantitévendu').value;
            const enstock = document.getElementById('enstock');

            if (quantitépc && quantitévendu) {
                enstock.value = (quantitépc - quantitévendu).toFixed(0);
            } else {
                enstock.value = '';
            }
        }


        function calculateMarge() {
const prixachat = parseFloat(document.getElementById('prixachat').value) || 0;
const quantitévendu = parseFloat(document.getElementById('Quantitévendu').value) || 0;
const totalvendu = parseFloat(document.getElementById('totalvendu').value) || 0;
const marge = document.getElementById('marge');

const totalAchat = quantitévendu * prixachat;
marge.value = (totalvendu - totalAchat).toFixed(2);
}

    </script>
    <script>
        document.getElementById('updateButton').addEventListener('click', function (event) {
            event.preventDefault(); // Empêche la soumission immédiate du formulaire
            const form = event.target.closest('form'); // Récupère le formulaire associé
    
            // Envoie les données via AJAX
            fetch(form.action, {
                method: form.method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(Object.fromEntries(new FormData(form)))
            })
            .then(response => {
                if (response.ok) {
                    // Si la requête est réussie
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Vente modifiée avec succès.',
                        confirmButtonText: 'OK'
                       
                    }).then(() => {
                        form.submit(); // Soumet le formulaire après l'affichage
                    });
                } else {
                    // Si une erreur survient
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue. Veuillez réessayer.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur Inattendue',
                    text: 'Une erreur s’est produite. Veuillez contacter l’administrateur.',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
    
    @endcan
</x-app-layout>
