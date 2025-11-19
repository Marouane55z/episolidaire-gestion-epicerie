<div class="listeLegere">

    <!-- Titre du formulaire -->
    <h3>Ajouter un nouveau commerce : </h3>

    <!-- Formulaire pour ajouter un commerce -->
    <form method="POST" action="index.php?uc=listeCommerces&action=ajouterUnCommerce">
        
        <!-- Tableau pour organiser les champs du formulaire -->
        <table>
            <tr>
                <td>Nom : </td>
                <td>
                    <!-- Champ pour le nom du commerce -->
                    <input type='text' name='nom' size='50' maxlength='50'>
                </td>
            </tr>
            <tr>
                <td>Rue :</td>
                <td>
                    <!-- Champ pour la rue -->
                    <input type='text' name='rue' size='50' maxlength='50'>
                </td>
            </tr>
            <tr>
                <td>Code Postal :</td>
                <td>
                    <!-- Champ pour le code postal -->
                    <input type='text' name='cp' size='5' maxlength='10'>
                </td>
            </tr>
            <tr>
                <td>Ville :</td>
                <td>
                    <!-- Champ pour la ville -->
                    <input type='text' name='ville' size='50' maxlength='50'> 
                </td>
            </tr>
        </table>
        
        <br />

        <!-- Boutons pour valider ou réinitialiser le formulaire -->
        <input type='submit' value='Enregister' name='valider'>
        <input type='reset' value='Annuler' name='annuler'>
    
    </form>

    <p></p>

</div>
