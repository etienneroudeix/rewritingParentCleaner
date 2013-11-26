<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : http://www.octolys.fr						   							 */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 2 of the License, or            */
/*      (at your option) any later version.                                          */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program; if not, write to the Free Software                  */
/*      Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    */
/*                                                                                   */
/*************************************************************************************/

include_once(__DIR__ . "/../../../classes/PluginsClassiques.class.php");

class RewritingParentCleaner extends PluginsClassiques
{
	public function __construct()
    {
		parent::__construct("rewritingParentCleaner");
	}

    public function listProductErrors()
    {
        $errorList = array();
        foreach( $this->query_liste("SELECT * FROM " . Reecriture::TABLE . " WHERE fond='produit'", "Reecriture") as $reecriture ) {
            parse_str($reecriture->param, $data);
            if(!isset($data['id_produit'])) {
                continue;
            }
            $produit = new Produit();
            if(!$produit->charger_id($data['id_produit'])) {
                continue;
            }
            if($produit->rubrique != $data['id_rubrique']) {
                $errorList[] = array(
                    "reecriture" => $reecriture,
                    "param" => $data,
                    "produit" => $produit,
                );
            }
        }
        return $errorList;
    }

    public function listContentErrors()
    {
        $errorList = array();
        foreach( $this->query_liste("SELECT * FROM " . Reecriture::TABLE . " WHERE fond='contenu'", "Reecriture") as $reecriture ) {
            parse_str($reecriture->param, $data);
            if(!isset($data['id_contenu'])) {
                continue;
            }
            $contenu = new Contenu();
            if(!$contenu->charger_id($data['id_contenu'])) {
                continue;
            }
            if($contenu->dossier != $data['id_dossier']) {
                $errorList[] = array(
                    "reecriture" => $reecriture,
                    "param" => $data,
                    "contenu" => $contenu,
                );
            }
        }
        return $errorList;
    }

    public function clean()
    {
        $productErrorList = $this->listProductErrors();
        foreach($productErrorList as $productError) {
            $productError['param']['id_rubrique'] = $productError['produit']->rubrique;

            $productError['reecriture']->param = '&' . http_build_query($productError['param']);
            $productError['reecriture']->maj();
        }

        $contentErrorList = $this->listContentErrors();
        foreach($contentErrorList as $contentError) {
            $contentError['param']['id_dossier'] = $contentError['contenu']->dossier;

            $contentError['reecriture']->param = '&' . http_build_query($contentError['param']);
            $contentError['reecriture']->maj();
        }
    }
}
?>
