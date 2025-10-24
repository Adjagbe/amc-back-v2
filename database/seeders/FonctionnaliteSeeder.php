<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fonctionnalite;

class FonctionnaliteSeeder extends Seeder
{
    public function run(): void
    {
        // Nettoyer les anciennes fonctionnalités
        $this->command->info('Nettoyage des anciennes fonctionnalités...');
        Fonctionnalite::truncate();
        
        $fonctionnalites = [
            // Gestion des Membres
            ['libelle' => 'Voir les membres', 'code' => 'voir_membres'],
            ['libelle' => 'Ajouter un membre', 'code' => 'ajouter_membre'],
            ['libelle' => 'Modifier un membre', 'code' => 'modifier_membre'],
            ['libelle' => 'Supprimer un membre', 'code' => 'supprimer_membre'],
            ['libelle' => 'Voir détails membre', 'code' => 'voir_details_membre'],
            
            // Départements
            ['libelle' => 'Voir les départements', 'code' => 'voir_departements'],
            ['libelle' => 'Ajouter un département', 'code' => 'ajouter_departement'],
            ['libelle' => 'Modifier un département', 'code' => 'modifier_departement'],
            ['libelle' => 'Supprimer un département', 'code' => 'supprimer_departement'],
            ['libelle' => 'Accès département Enfants', 'code' => 'acces_enfants'],
            ['libelle' => 'Accès département Adolescents', 'code' => 'acces_adolescents'],
            ['libelle' => 'Accès département Jeunesse', 'code' => 'acces_jeunesse'],
            ['libelle' => 'Accès département Chorale', 'code' => 'acces_chorale'],
            ['libelle' => 'Accès département Rayon Soleil', 'code' => 'acces_rayon_soleil'],
            ['libelle' => 'Accès département Service Ordre', 'code' => 'acces_service_ordre'],
            ['libelle' => 'Accès département Service Social', 'code' => 'acces_service_social'],
            ['libelle' => 'Accès département Multimédia', 'code' => 'acces_multimedia'],
            ['libelle' => 'Accès département UFM', 'code' => 'acces_ufm'],
            ['libelle' => 'Accès département UHM', 'code' => 'acces_uhm'],
            ['libelle' => 'Accès département Auxiliaires Lydie', 'code' => 'acces_auxiliaires_lydie'],
            ['libelle' => 'Accès département Auxiliaires Jeunes Filles', 'code' => 'acces_auxiliaires_jf'],
            
            // Rôles et Permissions
            ['libelle' => 'Voir les rôles', 'code' => 'voir_roles'],
            ['libelle' => 'Ajouter un rôle', 'code' => 'ajouter_role'],
            ['libelle' => 'Modifier un rôle', 'code' => 'modifier_role'],
            ['libelle' => 'Supprimer un rôle', 'code' => 'supprimer_role'],
            ['libelle' => 'Gérer les permissions', 'code' => 'gerer_permissions'],
            ['libelle' => 'Voir les fonctionnalités', 'code' => 'voir_fonctionnalites'],
            
            // Journaux d'activité
            ['libelle' => 'Voir les journaux', 'code' => 'voir_logs'],
            
            // Tableau de bord
            ['libelle' => 'Voir le tableau de bord', 'code' => 'voir_dashboard'],
            ['libelle' => 'Voir les statistiques', 'code' => 'voir_statistiques'],
            
            // Finance
            ['libelle' => 'Voir les finances', 'code' => 'voir_finances'],
            ['libelle' => 'Ajouter une transaction', 'code' => 'ajouter_transaction'],
            ['libelle' => 'Modifier une transaction', 'code' => 'modifier_transaction'],
            ['libelle' => 'Supprimer une transaction', 'code' => 'supprimer_transaction'],
            
            // Événements
            ['libelle' => 'Voir les événements', 'code' => 'voir_evenements'],
            ['libelle' => 'Ajouter un événement', 'code' => 'ajouter_evenement'],
        ];

        $this->command->info('Insertion des nouvelles fonctionnalités...');
        foreach ($fonctionnalites as $fonctionnalite) {
            Fonctionnalite::create($fonctionnalite);
        }
        
        $this->command->info('✅ ' . count($fonctionnalites) . ' fonctionnalités créées avec succès !');
    }
}
