<?php 
    require_once './_request.php';
    require_once './tools/_tools.php';

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            if (!_isset_key($_POST, 'action')) {
                echo json_encode(['error' => 'Missing action parameter']);
                break;
            }

            switch ($_POST['action']) {
                case 'ajout_service':
                    $data = _create_filters($_POST, ['action']);
                    $service = new Service();

                    $service->setNom($data['NOM']);
                    $service->setDateDebut($data['DATE_DEBUT']);
                    $service->setDateFin($data['DATE_FIN']);
                    $service->setLundi($data['LUNDI'] == 'false' ? 0 : 1);
                    $service->setMardi($data['MARDI'] == 'false' ? 0 : 1);
                    $service->setMercredi($data['MERCREDI'] == 'false' ? 0 : 1);
                    $service->setJeudi($data['JEUDI'] == 'false' ? 0 : 1);
                    $service->setVendredi($data['VENDREDI'] == 'false' ? 0 : 1);
                    $service->setSamedi($data['SAMEDI'] == 'false' ? 0 : 1);
                    $service->setDimanche($data['DIMANCHE'] == 'false' ? 0 : 1);

                    try {
                        $serviceID = $service->create();
                    } catch (Exception $e) {
                        header("Location: ./q3.php?error=failed_service_creation");
                        break;
                    }


                    if (_isset_key($data, 'EXCEPTIONS') && trim($data['EXCEPTIONS'])) {
                        $lines = explode("\n", $data['EXCEPTIONS']);
                        if(count($lines) > 0) {
                            foreach ($lines as $line) {
                                $parts = explode(' ', trim($line));
                                if (count($parts) == 2) {
                                    $date = $parts[0];
                                    $inclus = strtoupper($parts[1]) == 'INCLUS' ? 1 : 2;
                                    $exception = ExceptionService::withData($service->getID(), $date, $inclus);

                                    try {
                                        $exception->create();
                                    } catch (Exception $e) {
                                        $service->delete();
                                        header("Location: ./q3.php?error=failed_exception_creation");
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    header("Location: ./index.php");
                    break;
                
                case 'supprimer_itineraire':
                    $data = _create_filters($_POST, ['action']);
                    $itineraire = new Itineraire();

                    try {
                        $itineraire->deleteById($data['INTINERAIRE_ID']);
                    } catch (Exception $e) {
                        header("Location: ./q7.delete.php?error=failed_itineraire_deletion");
                        break;
                    }

                    header("Location: ./index.php");
                    break;
                
                case 'ajout_horaire':
                    $data = _create_filters($_POST, ['action']);
                    $trajet = Trajet::withData($data['INTINERAIRE_ID']."::".$data['DIRECTION'], 0, intval($data['INTINERAIRE_ID']), intval($data['DIRECTION']));

                    $horaire->setItineraireId($data['INTINERAIRE_ID']);
                    $horaire->setDirection($data['DIRECTION']);
                    $horaire->setHoraire($data['HORAIRE']);

                    try {
                        $horaireID = $horaire->create();
                    } catch (Exception $e) {
                        header("Location: ./q7.add.php?error=failed_horaire_creation");
                        break;
                    }

                    header("Location: ./index.php");
                    break;

                default:
                    echo json_encode(['error' => 'Unknown action']);
            }


            break;
        
        case 'GET':
            break;

        default:
            echo json_encode(['error' => 'Invalid request method']);
    }
?>