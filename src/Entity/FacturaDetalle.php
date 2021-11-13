<?php

namespace App\Entity;

use App\Repository\FacturaDetalleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturaDetalleRepository::class)
 */
class FacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $factura_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $producto;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $precio;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $subtotal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacturaId(): ?int
    {
        return $this->factura_id;
    }

    public function setFacturaId(int $factura_id): self
    {
        $this->factura_id = $factura_id;

        return $this;
    }

    public function getProducto(): ?string
    {
        return $this->producto;
    }

    public function setProducto(string $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getSubtotal(): ?string
    {
        return $this->subtotal;
    }

    public function setSubtotal(string $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }
}
