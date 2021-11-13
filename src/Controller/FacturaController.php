<?php

namespace App\Controller;

use App\Entity\Factura;
use App\Entity\FacturaDetalle;
use App\Repository\FacturaRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FacturaController extends AbstractController
{

    /**
     * @Route("/home")
     */
    public function index()
    {
        $fecha = date('d/m/Y', time());
        return $this->render('home.html.twig', [
            'fecha' => $fecha,
        ]);
    }

    /**
     * @Route("facturas/list")
     */
    public function list(Request $request, FacturaRepository $facturaRepository)
    {
        $facturas = $facturaRepository->findAll();
        $facturas_array = [];
        foreach($facturas as $factura) {
            $facturas_array[] = [
                'id' => $factura->getId(),
                'fecha' => $factura->getFecha(),
                'cliente' => $factura->getCliente(),
                'total' => $factura->getTotal(),
                'ciudad' => $factura->getCiudad()
            ];
        }
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => $facturas_array
        ]);
        return $response;
    }

    /**
     * @Route("facturas/create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $emi, LoggerInterface $logger)
    {
        $response = new JsonResponse();
        // cabecera factura
        $txtFecha = explode('/', $request->get('txtFecha', null));
        $fecha = $txtFecha[2] . '-' . $txtFecha[1] . '-' . $txtFecha[0];
        $cliente = $request->get('txtCliente', null);
        $ciudad = $request->get('txtCiudad', null);
        $total = $request->get('txtTotal', 0);
        // detalle factura
        $producto = json_decode($request->get('txtProducto', null));
        $precio = json_decode($request->get('txtPrecio', null));
        $cantidad = json_decode($request->get('txtCantidad', null));
        $subtotal = json_decode($request->get('txtSubtotal', null));
        if (
            empty($fecha) || empty($cliente) || empty($ciudad) || empty($total) ||
            empty($producto) || empty($precio) || empty($cantidad) || empty($subtotal)
        ) {
            $response->setData([
                'success' => false,
                'error' => 'Faltan datos para completar la operación.',
                'data' => null
            ]);
            return $response;
        }
        // cabecera factura
        $factura = new Factura();
        $factura->setFecha(DateTime::createFromFormat('Y-m-d', $fecha));
        $factura->setCliente($cliente);
        $factura->setCiudad($ciudad);
        $factura->setTotal($total);
        $emi->persist($factura);
        $emi->flush();
        // detalle factura
        for($i = 0; $i < count($producto); $i ++) {
            $factura_detalle = new FacturaDetalle();
            $factura_detalle->setFacturaId($factura->getId());
            $factura_detalle->setProducto($producto[$i]);
            $factura_detalle->setPrecio($precio[$i]);
            $factura_detalle->setCantidad((int)$cantidad[$i]);
            $factura_detalle->setSubtotal($subtotal[$i]);
            $emi->persist($factura_detalle);
            $emi->flush();
        }

        $response->setData([
            'success' => true,
            'data' => [
                'id' => $factura->getId()
            ]
        ]);
        $logger->info('Create factura success', [$factura, $factura_detalle]);
        return $response;
    }
}
