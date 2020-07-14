<?php

namespace Drupal\sayan_qr\Plugin\Block;

use Drupal\node\Entity\Node;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a QRCode Block.
 * use Endroid\QrCode\QrCode;

 * @Block(
 *   id = "qrcode",
 *   admin_label = @Translation("QRCode"),
 *   category = @Translation("QRCode"),
 * )
 */
class Code extends BlockBase
{

    /**
     * {@inheritdoc}
     */
    public function build()
    {

        global $base_url;
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node instanceof \Drupal\node\NodeInterface && $node->getType() == 'products') {
            // You can get nid and anything else you need from the node object.
            //if($node->type == "products"){
            $nid = $node->id();
            // text field
            $node_details = Node::load($nid);
            $txt = $node_details->field_app_purchase_link->uri;

            $output_path = \Drupal::service('file_system')->realpath(file_default_scheme() . "://"). "/qrcode$nid.png";
            \PHPQRCode\QRcode::png($txt, $output_path, 'L', 4, 2);

            $build['#markup'] = "<img src=".$base_url."/sites/default/files/qrcode$nid.png/>";
            $build['#cache']['max-age'] = 0;

        } else {

            $build['#markup'] = $this->t("No QRCODES Here");
        }

        return $build;
    }

}
