<?php 
    require_once './_request.php';
    require_once './tools/_tools.php';

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            if (!_isset_key($_POST, 'action')) {
                echo '<b>Missing action parameter</b>';
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
                    $trajet = new Trajet();

                    $trajet->setItineraireId(intval($data['INTINERAIRE_ID']));
                    $trajet->setDirection(intval($data['DIRECTION']));
                    $trajet->setServiceId(intval($data['SERVICE_ID']));
                    $trajet->setTrajetId($data['INTINERAIRE_ID']."::".$data['SERVICE_ID']."::".$data['DIRECTION']."::".date("H:i"));

                    try {
                        $trajet->create();
                    } catch (Exception $e) {
                        header("Location: ./q7.add.php?error=failed_trajet_creation");
                        break;
                    }

                    if (_isset_key($data, 'HORAIRE') && trim($data['HORAIRE'])) {
                        $lines = explode("\n", $data['HORAIRE']);
                        if(count($lines) > 0) {
                            foreach ($lines as $line) {
                                $parts = explode(',', trim($line));
                                if (count($parts) >= 3) {

                                    $arrive = $parts[1];
                                    $depart = $parts[2];

                                    if (trim($arrive) && trim($depart)) {
                                        if (!isValidTime($arrive) || !isValidTime($depart)) {
                                            $trajet->delete();
                                            header("Location: ./q7.add.php?error=invalid_horaire_format");
                                            exit();
                                        }

                                        $arrive = DateTime::createFromFormat('H:i', $arrive);
                                        $depart = DateTime::createFromFormat('H:i', $depart);

                                        if ($arrive >= $depart) {
                                            $trajet->delete();
                                            header("Location: ./q7.add.php?error=invalid_horaire");
                                            exit();
                                        }

                                        $arrive = $arrive->format('H:i');
                                        $depart = $depart->format('H:i');
                                    }

                                    $horraire = Horraire::withData(
                                        $trajet->getTrajetId(),
                                        $trajet->getItineraireId(),
                                        $parts[0],
                                        $arrive,
                                        $depart
                                    );

                                    try {
                                        $horraire->create();
                                    } catch (Exception $e) {
                                        $trajet->delete();
                                        header("Location: ./q7.add.php?error=failed_horraire_creation");
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    header("Location: ./index.php");
                    break;
                
                case 'modifier_arret':
                    $data = _create_filters($_POST, ['action']);
                    $arret = new Arret();
    
                    $arret->setNom($data['NOM']);
                    $arret->setID($data['ID']);
                    $arret->setLatitude($data['LATITUDE']);
                    $arret->setLongitude($data['LONGITUDE']);
    
                    try {
                        $arret->update();
                    }
                    catch (InvalidArgumentException $e) {
                        header("Location: ./q8.update.php?error=invalid_boudary");
                        break;
                    }
                    catch (PDOException $e) {
                        header("Location: ./q8.update.php?error=failed_arret_update");
                        break;
                    }
    
                    header("Location: ./index.php");
    
    
                break;

                default:
                    echo '<b>unknow action</b>';
            }
        
        case 'GET':
            break;

        default:
            echo '<b>Invalid request parameter</b>';
    }
?>